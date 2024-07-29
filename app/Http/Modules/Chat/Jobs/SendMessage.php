<?php

namespace App\Modules\Chat\Jobs;

use App\Modules\Chat\Services\FileService;
use App\Modules\Chat\Services\MemberService;
use App\Modules\Chat\Services\MessageService;
use App\Modules\Chat\Services\RoomService;
use App\Services\SocketService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Traits\Notification;
use Illuminate\Support\Facades\DB;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Notification;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private array $data;
    private MessageService $messageService;
    private RoomService $roomService;
    private MemberService $memberService;
    private FileService $fileService;
    private SocketService $socketService;

    public function __construct($data){
        $this->data = $data;
    }

    public function handle(
        SocketService $socketService,
        MessageService $messageService,
        RoomService $roomService,
        FileService $fileService,
        MemberService $memberService){

        $this->socketService     = $socketService;
        $this->messageService    = $messageService;
        $this->roomService       = $roomService;
        $this->memberService     = $memberService;
        $this->fileService       = $fileService;

        return DB::transaction(function (){
            if($messageId = $this->messageService->addMessage($this->data)){
                $lastMessage = $this->messageService->getChatRepository()->getLenOfList('ROOM_MESSAGE', $this->data['room_id']);
                $members     = $this->roomService->getChatRepository()->getObject('ROOM_MEMBER', $this->data['room_id']);
                $hasToAll    = $this->memberService->checkContentHasMentionToAll($this->data['content']);
                $this->socketService->connect();

                if($hasToAll){
                    $this->socketService->emit([
                        'channel' => 'ROOM_' . $this->data['room_id'],
                        'event'   => 'room_to_all',
                        'data'    => $this->data['room_id']
                    ]);
                }

                $this->memberService->updateMember(
                    $this->data['room_id'] .'_'.$this->data['auth'], [
                    'position'=> $lastMessage,
                    'mention' => 0,
                ]);

                $this->roomService->updateRoom(['total' => $lastMessage], $this->data['room_id']);
                $fileInsert  = $this->messageService->processFileInContentMessage($this->data);
                $files       = $this->messageService->getChatRepository()->getObjectsByList('FILE', $fileInsert);
                $room        = $this->messageService->getChatRepository()->getObject('ROOM', $this->data['room_id']);

                foreach ($members['ROOM_MEMBER'][$this->data['room_id']] as $member){
                    $hasTo = $this->memberService->checkContentHasMentionTo($this->data['room_id'], $member, $this->data['content']);
                    if($hasToAll || $hasTo){
                        $keyMember  = $this->data['room_id'] .'_'. $member;
                        $object     = $this->memberService->getChatRepository()->getObject('MEMBER', $keyMember);
                        $roomMember = $object['MEMBER'][$keyMember];
                        $this->memberService->updateMember($keyMember, [
                            'mention' => $roomMember['mention'] + 1
                        ]);
                        if ($hasTo){
                            $this->socketService->emit([
                                'channel' => 'USER_' . $member,
                                'event'   => 'user_update_member',
                                'data'    => $this->memberService->getChatRepository()->getObject('MEMBER', $keyMember)]
                            );
                        }
                        if($room['ROOM'][$this->data['room_id']]['type'] === 2){
                            $this->pushNotification('chat_thread_send', [
                                'tos'        => [$member],
                                'content'    => $this->data['content'],
                                'room_id'    => $this->data['room_id'],
                                'room_name'  => $room['ROOM'][$this->data['room_id']]['name'],
                                'position'   => $lastMessage,
                                'file_ids'   => $fileInsert
                            ]);
                        } else {
                            $this->pushNotification('chat_message_send', [
                                'tos'        => [$member],
                                'content'    => $this->data['content'],
                                'room_id'    => $this->data['room_id'],
                                'room_name'  => $room['ROOM'][$this->data['room_id']]['name'],
                                'position'   => $lastMessage,
                                'file_ids'   => $fileInsert
                            ]);
                        }
                    }
                }

                $this->socketService->emit([
                    'channel' => 'USER_' . $this->data['auth'],
                    'event'   => 'user_update_member',
                    'data'    => $this->memberService->getChatRepository()
                        ->getObject('MEMBER', $this->data['room_id'].'_'.$this->data['auth'])
                ]);

                $this->socketService->emit([
                    'channel' => 'ROOM_' . $this->data['room_id'],
                    'event'   => 'room_push_message',
                    'data'    => array_merge($files, $room,
                        $this->messageService->getChatRepository()->getObject('MESSAGE', $messageId),
                        $this->messageService->getLastList('ROOM_MESSAGE', $this->data['room_id']),
                    )
                ]);

                $this->socketService->disconnect();

                return $messageId;
            }

            return null;
        });
    }
}
