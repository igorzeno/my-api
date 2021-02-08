<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public $loginAfterSignUp = true;

    public function login(Request $request)
    {
        $credentials = $request->only("email", "password");
        $token = null;

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                "status" => false,
                "message" => "Unauthorized"
            ]);
        }

        return response()->json([
            "status" => true,
            "token" => $token
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:20',
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            $fieldsWithErrorMessagesArray = $validator->messages()->get('*');
            return response()->json([
                "status" => false,
                "errors" =>$fieldsWithErrorMessagesArray
            ]);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        echo 'User created...';

    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            "token" => "required"
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                "status" => true,
                "message" => "User logged out successfully"
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                "status" => false,
                "message" => "Ops, the user can not be logged out"
            ]);
        }
    }
}
