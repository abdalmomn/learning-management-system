<?php

namespace App\actions;

use App\Mail\SendCodeResetPassword;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ResetCodePasswordAction
{

    //function to delete the last code for this user and create a new one and send it to the user
    public function user_forgot_password($request) : array
    {
        ResetCodePassword::query()->where('email',$request['email'])->delete();

        $data['code'] = mt_rand(100000,999999);

        $codeData = ResetCodePassword::query()->create([
            'email' => $request['email'],
            'code' => $data['code'],
            'created_at' => now(),
        ]);

        Mail::to($request['email'])->send(new SendCodeResetPassword($codeData['code']));
        return [
            'data' => $codeData,
            'message' => 'send the new code to the input email',
            'code' => 200,
        ];
    }

    //function to tell the user there is a code send to him pleas enter this code to check
    public function user_check_code($request) : array
    {
        $passwordReset = ResetCodePassword::query()->firstWhere('code',$request['code']);
        if ($passwordReset['created_at'] > now()->addHour()){
            $passwordReset->delete();
            return [
                'data' => [],
                'message' => 'This code is expired',
                'code' => 422,
            ];

        }

        return [
            'data' => $passwordReset['code'],
            'message' => 'The code is correct',
            'code' => 200,
        ];
    }

    //function to take the code with the new password
    public function user_reset_password($request) : array
    {
        $passwordReset = ResetCodePassword::query()->firstWhere('code',$request['code']);
        if ($passwordReset['created_at'] > now()->addHour()){
            $passwordReset->delete();
            return [
                'data' => [],
                'message' => 'This code is expired',
                'code' => 422,
            ];
        }

        $user = User::query()->where('email',$passwordReset['email'])->first();
        $pass = bcrypt($request['password']);

        $user->update([
            'password' => $pass,
        ]);
        $passwordReset->delete();

        return [
            'data' => $user,
            'message' => 'Password has been successfully reset',
            'code' => 200,
        ];
    }

}
