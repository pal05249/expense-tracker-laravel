<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserProfilePostRequest;
use App\Http\Requests\UserResetPasswordPostRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    /**
     * Update user profile info such as name and email
     *
     * 
     *
     * @param UserProfilePostRequest $request user profile form request
     * @param int $userId user id
     * @return Response 
     **/
    public function UpdateUserDetails(UserProfilePostRequest $request, $userId)
    {
        User::where('id',$userId)->update(['name'=>$request->name,'email'=>$request->email]);
        return response()->json(['message'=>'Successfully updated'],200);
    }
    /**
     * Reset user password
     *
     * 
     *
     * @param UserResetPasswordPostRequest $request user password form request
     * @param int $userId user id
     * @return Response 
     **/
    public function updatePassword(UserResetPasswordPostRequest $request, $userId)
    {
        User::where('id',$userId)->update(['password'=>Hash::make($request->password)]);
        return response()->json(['message'=>'Successfully updated'],200);
    }
}
