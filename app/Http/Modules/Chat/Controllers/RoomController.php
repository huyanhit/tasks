<?php

namespace App\Modules\Chat\Controllers;

use App\Exceptions\ProcessException;
use App\Http\Controllers\BaseController;
use App\Modules\Chat\Requests\AddModuleRoomRequest;
use App\Modules\Chat\Requests\AddRoomRequest;
use App\Modules\Chat\Requests\GetMessageRequest;
use App\Modules\Chat\Requests\GetModuleRoomRequest;
use App\Modules\Chat\Requests\GetRoomRequest;
use App\Modules\Chat\Requests\JoinRoomRequest;
use App\Modules\Chat\Requests\UpdateModuleRoomRequest;
use App\Modules\Chat\Requests\UpdateRoomsRequest;
use App\Modules\Chat\Services\MessageService;
use App\Modules\Chat\Services\RoomService;
use App\Modules\Chat\Services\MemberService;
use App\Services\SocketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Traits\Chat;

class RoomController extends BaseController
{
    use Chat;
    private RoomService $roomService;
    private MemberService $memberService;
    private MessageService $messageService;
    private SocketService $socketService;

    public function __construct(
        SocketService $socketService,
        RoomService $roomService,
        MemberService $memberService,
        MessageService $messageService
    ){

        $this->socketService  = $socketService;
        $this->roomService    = $roomService;
        $this->memberService  = $memberService;
        $this->messageService = $messageService;
    }

    public function getMyRooms(): JsonResponse
    {
        try {
            return $this->sendResponse($this->roomService->getMyRooms());
        }catch (\Exception $e){
            throw new ProcessException($e);
        }
    }

    public function joinRoom(JoinRoomRequest $request): JsonResponse
    {
        return $this->sendResponse(true);
    }

    public function getModuleRoom(GetModuleRoomRequest $request): JsonResponse
    {
        try {
            return $this->sendResponse($this->getDataModuleRoom($request->all()));
        }catch (\Exception $e){
            throw new ProcessException($e);
        }
    }

    public function createModuleRoom(AddModuleRoomRequest $request): JsonResponse
    {
        try {
            return $this->sendResponse($this->createDataModuleRoom($request->input()));
        }catch (\Exception $e){
            throw new ProcessException($e);
        }
    }

    public function updateModuleRoom(UpdateModuleRoomRequest $request): JsonResponse
    {
        try {
            return $this->sendResponse($this->updateDataModuleRoom($request->input()));
        }catch (\Exception $e){
            throw new ProcessException($e);
        }
    }

    public function index(): JsonResponse
    {
        try {
            return $this->sendResponse($this->roomService->getAllRoom());
        }catch (\Exception $e){
            throw new ProcessException($e);
        }
    }

    public function show(GetRoomRequest $request, $roomId): JsonResponse
    {
        try {
            $room = $this->roomService->getRoomInfo($roomId);
            $messages = $this->messageService->getMessages([
                'room_id'=> $roomId,
                'position'=> $request->get('position'),
                'type'=> $request->get('type')
            ]);
            $members = $this->memberService->getMembers($roomId);

            return $this->sendResponse(array_merge($messages, $members, $room));
        }catch (\Exception $e){
            throw new ProcessException($e);
        }
    }

    public function store(AddRoomRequest $request): JsonResponse
    {
        try {
            $data = $request->input();
            $key  = $this->roomService->addRoom($data);
            $this->memberService->addMembersIntoRoom($key, $request->get('members'));
            $room       = $this->roomService->getChatRepository()->getObject('ROOM', $key);
            $roomMember = $this->roomService->getChatRepository()->getObject('ROOM_MEMBER', $key);
            $members    = $this->memberService->getMembersByUserIds($key, $roomMember['ROOM_MEMBER'][$key]);
            $authRoom   = $this->roomService->getChatRepository()->getObject('MY_ROOM', Auth::id());
            $this->socketService->connect();

            foreach ($roomMember['ROOM_MEMBER'][$key] as $memberId){
                $myRoom = $this->roomService->getChatRepository()->getObject('MY_ROOM', $memberId);
                $result = array_merge($room, $roomMember, $members, $myRoom);
                $this->socketService->emit([
                    'channel' => 'USER_' . $memberId,
                    'event'   => 'user_add_room',
                    'data'    => $result
                ]);
            }

            $this->socketService->disconnect();
            return $this->sendResponse(array_merge($room, $roomMember, $members, $authRoom));
        }catch (\Exception $e){
            throw new ProcessException($e);
        }
    }

    public function update(UpdateRoomsRequest $request, $roomId): JsonResponse
    {
        try {
            $this->roomService->updateRoom($request->input(), $roomId);
            if($request->get('members')){
                $membersUpdate = $this->memberService->updateMembers($roomId, $request->get('members'));
                $room       = $this->roomService->getChatRepository()->getObject('ROOM', $roomId);
                $roomMember = $this->roomService->getChatRepository()->getObject('ROOM_MEMBER', $roomId);
                $members    = $this->memberService->getMembersByUserIds($roomId, $roomMember['ROOM_MEMBER'][$roomId]);
                $authRoom   = $this->roomService->getChatRepository()->getObject('MY_ROOM', Auth::id());

                $this->socketService->connect();
                foreach ($membersUpdate as $id){
                    $myRoom = $this->roomService->getChatRepository()->getObject('MY_ROOM', $id);
                    $result = array_merge($room, $roomMember, $members, $myRoom);
                    $this->socketService->emit([
                        'channel' => 'USER_' . $id,
                        'event'   => 'user_update_member',
                        'data'    => $result
                    ]);
                }
                $this->socketService->disconnect();

                return $this->sendResponse(array_merge($room, $roomMember, $members, $authRoom));
            }else{
                return $this->sendResponse($this->roomService->getChatRepository()->getObject('ROOM', $roomId));
            }
        }catch (\Exception $e){
            throw new ProcessException($e);
        }
    }

    public function destroy($roomId): JsonResponse
    {
        try {
            $key = $this->roomService->deleteRoom($roomId);
            $result = array_merge(
                $this->roomService->getChatRepository()->getObject('MY_ROOM', Auth::id()),
                ['ROOM' => [$key => null]]
            );

            return $this->sendResponse($result, 'remove room success');
        }catch (\Exception $e){
            throw new ProcessException($e);
        }
    }
}
