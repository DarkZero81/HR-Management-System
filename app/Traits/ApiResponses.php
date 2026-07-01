<?php

namespace App\Traits;

trait ApiResponses{
    protected function ok(mixed $message){
        return $this->success($message,200);
    }


    protected function success (mixed $message,$statuscode = 200){
          return response()->json([
            'msg' => $message,
            'code' => $statuscode,
        ], $statuscode);
    }
}
