<?php
namespace App\Modules\Chat\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class FileService extends ChatService
{
    public function getMyFile(){
        $myFile = $this->getLastListMyFile();
        return array_merge($myFile, $this->getChatRepository()->getObjectsByList('FILE', $myFile['MY_FILE'][Auth::id()]));
    }

    public function getRoomFile($roomId){
        $roomFile = $this->getLastListRoomFile($roomId);
        return array_merge($roomFile, $this->getChatRepository()->getObjectsByList('FILE', $roomFile['ROOM_FILE'][$roomId]));
    }

    public function getLastListMyFile(){
        return ['MY_FILE' => [Auth::id() =>
            $this->getChatRepository()->getList('MY_FILE',  Auth::id(), null, -30, -1)]];
    }

    public function getLastListRoomFile($roomId){
        return ['ROOM_FILE' => [$roomId =>
            $this->getChatRepository()->getList('ROOM_FILE',  $roomId, null, -30, -1)]];
    }

    public function getFileThumbnail($fileId){
        ob_end_clean();
        $objectFile = $this->getChatRepository()->getObject('FILE', $fileId);
        if($objectFile['FILE'][$fileId]['type'] === 'image'){
            $path = config('constants.chat.thumbnail_separate').$objectFile['FILE'][$fileId]['path'];
            $file = Storage::disk($objectFile['FILE'][$fileId]['store'])->get($path);
            $type = Storage::mimeType($path);

            return response($file)->header('Content-Type', $type);
        }

        return null;
    }

    public function getFileRaw($fileId){
        ob_end_clean();
        $objectFile = $this->getChatRepository()->getObject('FILE', $fileId);
        $file = Storage::disk($objectFile['FILE'][$fileId]['store'])->get($objectFile['FILE'][$fileId]['path']);
        $type = Storage::mimeType($objectFile['FILE'][$fileId]['path']);

        return response($file)->header('Content-Type', $type);

    }

    public function uploadFiles($data){
        return DB::transaction(function () use ($data) {
            $files = $data['file'];
            $fileIds = [];
            foreach ($files as $file) {
                $upload = $this->uploadFile($file);
                $fileId = $this->getChatRepository()->addObject('FILE', null, $upload);
                $this->getChatRepository()->addObject('MY_FILE', null, ['user_id' => Auth::id(), 'file_id' => $fileId]);
                $this->getChatRepository()->addObject('ROOM_FILE', null, ['room_id' => $data['room_id'], 'file_id' => $fileId]);
                $fileIds[] = $fileId;
            }

            return $fileIds;
        });
    }

    public function uploadFile($file, $type = null, $module = 'chat'){
        $path       = getPathFolder($module);
        $name       = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $ext        = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        $store      = config('constants.chat.store_file');
        $path       = $path.'/'.$name;
        $pathStore  = $path.'.'.$ext;
        $run        = 1;
        $type       = 'file';

        if(in_array(strtolower($ext), config('constants.chat.image_type'))) {
            $type = 'image';
        }
        if(in_array(strtolower($ext), config('constants.chat.video_type'))) {
            $type = 'video';
        }

        while($run){
            if(Storage::disk($store)->exists($pathStore)){
                $pathStore = $path.'-'.$run.'.'.$ext;
            }else{
                Storage::disk($store)->put($pathStore, file_get_contents($file));
                if($type === 'image'){
                    Storage::disk($store)->put(config('constants.chat.thumbnail_separate').$pathStore,
                    Image::make($file)->orientate()->resize(null, 100, function ($constraint) {
                        $constraint->aspectRatio();
                    })->stream());
                }
                break;
            }
            $run ++;
        }

        return [
            'name'  => $name,
            'ext'   => $ext,
            'type'  => $type,
            'store' => $store,
            'path'  => $pathStore,
        ];
    }
}
