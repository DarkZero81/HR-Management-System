<?php

namespace App\Http\Controllers;
use App\Traits\ApiResponses;

class AuthController extends Controller
{
    use ApiResponses;
    public function show()
    {
        return $this->ok('hello');
        // dd('hello'); 
        // return response()->json([
        //     'msg' => 'Fuckyou',
        // ], 200);
    }
}
