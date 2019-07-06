<?php

namespace App\Http\Controllers;

use App\Exceptions\RegisterVerificationException;
use App\Exceptions\UserAlreadyRegisteredException;
use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(RegisterNewUserRequest $request)
    {
        $field = $request->has('email') ? 'email' : 'mobile';
        $value = $request->input($field);
        $code = random_int(111111, 999999);
        // check user exist
        $user = User::where($field, $value)->first();

        if ($user) {
            // if user verified before
            if ($user->verified_at) {
                throw new UserAlreadyRegisteredException('شما قبلا ثبت نام کرده ایید !');
            }
            // code send before
            return response(['message' => 'کد فعالسازی قبلا برای شما ارسال شده است !'], 200);
        }
        // create user and verify_code
        $user = User::create([
            $field => $value,
            'verify_code' => $code,
        ]);

        //Todo: send email or message to user
        Log::info('SEND-REGISTER-CODE-MESSAGE-TO-USER', ['code' => $code]);
        return response(['message' => 'کاربر ثبت موقت شد'], 200);
    }

    public function registerVerify(RegisterVerifyUserRequest $request)
    {
        $field = $request->has('email') ? 'email' : 'mobile';
        $code = $request->code;
        $user = User::where([
            'verify_code'=> $code,
            $field => $request->input($field)
        ])->first();

        if (empty($user)) {
            throw new ModelNotFoundException('کاربری پیدا نشد');
        }

        $user->verify_code = null;
        $user->verified_at = now();
        $user->save();

        return response($user, 200);
    }
}
