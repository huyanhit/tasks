<?php

namespace App\Modules\Chat\Services;

use Illuminate\Support\Facades\Auth;

class PermissionService extends ChatService {

    public function checkAuthInRoom($request): bool
    {
        if(empty($request->room_id)) $request->room_id = $request->route('room');
        $object = $this->getChatRepository()->getObject('MY_ROOM', Auth::id());
        return in_array($request->room_id, $object['MY_ROOM'][Auth::id()]);
    }

    public function checkAuthInComment($request): bool
    {
        $object = $this->getChatRepository()->getObject('MY_COMMENT', Auth::id());
        return in_array($request->room_id, $object['MY_COMMENT'][Auth::id()]);
    }

    public function checkMessageForAuth($request): bool
    {
        $key    = $request->room_id.'_'.$request->route('message');
        $object = $this->getChatRepository()->getObject('MESSAGE', $key);
        return $object['MESSAGE'][$key] && $object['MESSAGE'][$key]['auth'] == Auth::id();
    }

    public function checkRoleUpdateUser($request): bool
    {
        return true;
    }

    public function checkRoleDeleteUser($request): bool
    {
        return true;
    }

    public function checkUserInCompany($request): bool
    {
        return true;
    }
}
