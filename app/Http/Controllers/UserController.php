<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //


    public function register(Request $request)
    {
        $user = DB::table('users')->where('email', $request->email)->first();
        if ($user != null) {
            return response()->json([
                'error' => ['code' => 302, 'message' => 'email already exist'],
            ], Response::HTTP_OK);
        } else {
            $user = new User();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = md5($request->password);
            $user->fcmKey = $request->fcmKey;
            $user->city = $request->city;
            $user->emailVerified = $request->emailVerified;
            $user->picUrl = $request->picUrl;
            $user->save();
            return response()->json([
                'error' => ['code' => Response::HTTP_OK, 'message' => "false"]
                , 'user' => $user
            ], Response::HTTP_OK);


        }

    }

    public function login(Request $request)
    {
        $user = DB::table('users')->where('email', $request->email)
            ->where('password', md5($request->password))->first();
        if ($user != null) {
            $userr = User::find($user->id);
            $userr->fcmKey = $request->fcmKey;

            $userr->update();
            return response()->json([
                'error' => ['code' => 302, 'message' => 'false'],
                'user' => $userr], Response::HTTP_OK);
        } else {

            return response()->json([
                'error' => ['code' => Response::HTTP_OK, 'message' => "Wrong email or password"]

            ], Response::HTTP_OK);


        }

    }

    public function updateFcmKey(Request $request)
    {

        $userr = User::find($request->id);
        $userr->fcmKey = $request->fcmKey;

        $userr->update();
        return response()->json([
            'error' => ['code' => 302, 'message' => 'false'],
            'user' => $userr], Response::HTTP_OK);


    }

    public function allUsers(Request $request)
    {
        $users = DB::table('users')->where('city',$request->city)->get();

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => "false"],
            'user' => $users

        ], Response::HTTP_OK);

    }

}
