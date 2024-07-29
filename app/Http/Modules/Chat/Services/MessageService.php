<?php
namespace App\Modules\Chat\Services;

use Illuminate\Support\Facades\Auth;

class MessageService extends ChatService
{
    const MAX_LIST_MESSAGE       = 30;
    const NUMS_LOAD_MORE_MESSAGE = 10;
    const GET_MORE_MESSAGES_UP   = 1;
    const GET_MORE_MESSAGES_DOWN = 2;

    const FILE    = '/\[file:[\d]+\]|\[img:[\d]+\]/';
    const FILE_ID = '/[\d]+/';

    public function getMessages($data){
        if(empty($data['type'])){
            $positions   = $this->getPositionsById($data['room_id'], $data['position']);
            $roomMessage = $this->getChatRepository()->getList('ROOM_MESSAGE', $data['room_id'], null, $positions['start'], $positions['end']);
        }else{
            $positions   = $this->getPositionsByIdType($data['position'], $data['type']);
            $roomMessage = $this->getChatRepository()->getList('ROOM_MESSAGE', $data['room_id'], null, $positions['start'], $positions['end']);
            if($data['type'] == self::GET_MORE_MESSAGES_UP){
                $end = $positions['start'] + self::MAX_LIST_MESSAGE;
                $roomMessage = $this->getChatRepository()->getList('ROOM_MESSAGE', $data['room_id'], null, $positions['start'], $end);
            }
            if($data['type'] == self::GET_MORE_MESSAGES_DOWN){
                $start = $positions['end'] - self::MAX_LIST_MESSAGE;
                $start = ($start > 0) ? $start: 0;
                $roomMessage = $this->getChatRepository()->getList('ROOM_MESSAGE', $data['room_id'], null, $start, $positions['end']);
            }
        }
        $messages   = $this->getChatRepository()->getObjectsByList('MESSAGE', $roomMessage, $data['room_id']);
        $threads    = $this->getTheadMessage($messages);

        sort($roomMessage);

        return array_merge(['ROOM_MESSAGE' => [$data['room_id'] => $roomMessage]], $messages, $threads);
    }

    private function getTheadMessage($messages){
        $lists = [];
        $theadLists = [];
        $threads['THREAD'] = [];
        foreach($messages['MESSAGE'] as $value){
            if(isset($value['ids'])){
                $this->getTheads($value, $theadLists, $threads, $lists);
            }
        }

        return array_merge(array_merge($lists, $threads));
    }

    private function getTheads($value, &$theadLists, &$threads, &$lists){
        $theadList = $this->getChatRepository()->getList('MESSAGE_THREAD', $value['ids']);
        if($theadList){
            $theadLists = array_merge($theadLists, $theadList);
            $thread = $this->getChatRepository()->getObjectsByList('THREAD', $theadList, $value['ids']);
            foreach($thread['THREAD'] as $value){
                $this->getTheads($value, $theadLists, $threads, $lists);
            }
            $threads['THREAD'] = array_merge($threads['THREAD'], $thread['THREAD']) ;
            $lists['MESSAGE_THREAD'][$value['thread']] = $theadList;
        }
    }

    public function getMessage($data){
        $roomId    = $data['room_id'];
        $messageId = $data['message_id'];
        $key       = $roomId.'_'.$messageId;

        return $this->getChatRepository()->getObject('MESSAGE', $key);
    }

    public function addThread($data){
        if(isset($data['thread'])){
            $lastMID   = $this->getChatRepository()->getLenOfList('MESSAGE_THREAD', $data['thread']);
            $data      = array_merge($data, ['status' => 1, 'id' => $lastMID + 1]);

            return $this->getChatRepository()->addObject('THREAD', $data['thread'], $data);
        }
    }

    public function addMessage($data){
        $lastMID   = $this->getChatRepository()->getLenOfList('ROOM_MESSAGE', $data['room_id']);
        $data      = array_merge($data, ['status' => 1, 'id' => $lastMID + 1]);
        return $this->getChatRepository()->addObject('MESSAGE', $data['room_id'], $data);
    }

    public function updateMessage($messageId, $data){
        $data = array_merge($data, ['status' => 2]);
        $key  =  $data['room_id'].'_'.$messageId;

        return $this->getChatRepository()->updateObject('MESSAGE', $key, $data);
    }

    public function deleteMessage($data, $messageId){
        $key     = $data['room_id'].'_'.$messageId;
        $data    = ['status' => 0, 'content'=> ''];

        return $this->getChatRepository()->updateObject('MESSAGE',$key, $data);
    }

    public function getLastList($object, $roomId){
        return [$object => [$roomId => $this->getChatRepository()->getList($object, $roomId, null, -self::MAX_LIST_MESSAGE, -1)]];
    }

    public function processFileInContentMessage($data){
        $files = [];
        $content = $data['content'];
        preg_match_all(self::FILE, $content, $matches) ;
        foreach($matches[0] as $match){
            preg_match(self::FILE_ID, $match, $idMatch);
            $files[] = $idMatch[0];
        }

        return $files;
    }

    private function getPositionsById($roomId, $id){
        if(empty($id)){
            $keyMember = $roomId.'_'.Auth::id();
            $member = $this->getChatRepository()->getObject('MEMBER', $keyMember);
            if(isset($member['MEMBER'][$keyMember])){
                $id = $member['MEMBER'][$keyMember]['position'];
            }
        }

        $lastId = $this->getChatRepository()->getLenOfList('ROOM_MESSAGE', $roomId);
        $start  = $id - floor(self::MAX_LIST_MESSAGE/2);
        $end    = $id + floor(self::MAX_LIST_MESSAGE/2);

        if($end >= $lastId){
            $start = $lastId - self::MAX_LIST_MESSAGE;
        }
        if($start < 0){
            $start = 0;
        }

        return ['start' => $start, 'end' => $start + self::MAX_LIST_MESSAGE];
    }

    private function getPositionsByIdType($id, $type){
        $start = 0;
        if($type == self::GET_MORE_MESSAGES_UP){
            $start = ($id - self::NUMS_LOAD_MORE_MESSAGE > 0) ? ($id - self::NUMS_LOAD_MORE_MESSAGE - 1) : 0;
        }else if($type == self::GET_MORE_MESSAGES_DOWN){
            $start = $id - 1;
        }

        return ['start' => $start, 'end' => $start + self::NUMS_LOAD_MORE_MESSAGE];
    }
}
