<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthException;
use App\Exceptions\ProcessException;
use App\Services\PermissionService;
use http\Env\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const RESPONSE_SUCCESS    = 1;
    const RESPONSE_ERROR      = 0;
    const PROCESS_ERROR_CODE  = 2;

    const AUTH_ERROR_CODE     = 401;
    const FORBIDDEN_CODE      = 403;

    public function responseSuccess($data): JsonResponse{
        return response()->json(['status'=> self::RESPONSE_SUCCESS, 'data' => $data]);
    }
    public function responseAuth($data): JsonResponse{
        return response()->json(['status'=> self::RESPONSE_ERROR, 'data' => $data], self::AUTH_ERROR_CODE);
    }
    public function responseError($data, $code = self::PROCESS_ERROR_CODE): JsonResponse{
        return response()->json(['status'=> self::RESPONSE_ERROR, 'data' => $data, 'code' => $code], self::FORBIDDEN_CODE);
    }
}
