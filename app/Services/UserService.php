<?php

namespace App\Services;


use App\Exceptions\UserAlreadyRegisteredException;
use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Requests\Auth\ResendVerificationCodeRequest;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserService extends BaseService
{
    public static function registerNewUser(RegisterNewUserRequest $request)
    {
        try{
            DB::beginTransaction();
            $field = $request->getFieldName();
            $value = $request->getFieldValue();

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
            $code = randomVerificationCode();
            // create user and verify_code
            $user = User::create([
                $field => $value,
                'verify_code' => $code,
            ]);

            //Todo: send email or message to user
            Log::info('SEND-REGISTER-CODE-MESSAGE-TO-USER', ['code' => $code]);

            DB::commit();
            return response(['message' => 'کاربر ثبت موقت شد'], 200);
        }
        catch (\Exception $exception)
        {
            DB::rollBack();

            if ($exception instanceof UserAlreadyRegisteredException) {
                throw $exception;
            }

            Log::error($exception);
            return response(['message' => 'خطایی رخ داده است']);
        }
    }

    public static function registerNewUserVerify(RegisterVerifyUserRequest $request)
    {
        $field = $request->has('email') ? 'email' : 'mobile';
        $code = $request->code;
        $user = User::where([
            'verify_code'=> $code,
            $field => $request->input($field)
        ])->first();

        if (empty($user)) {
            throw new ModelNotFoundException('کاربری با اطلاعات مورد نظر پیدا نشد');
        }

        $user->verify_code = null;
        $user->verified_at = now();
        $user->save();

        return response($user, 200);
    }

    public static function resendVerificationCodeToUser(ResendVerificationCodeRequest $request)
    {
        $field = $request->getFieldName();
        $value = $request->getFieldValue();

        $user = User::where($field,$value)->whereNull('verified_at')->first();
        if (!empty($user)) {
            $dateDiff = now()->diffInMinutes($user->updated_at);
            if ($dateDiff > config('auth.resend_verification_code_time_diff')) {
                $code = randomVerificationCode();
                $user->verify_code = $code;
                $user->save();
            }else{
                $code = $user->verify_code;
            }
            //TODO:: // send code to mobile or email
            Log::info('RESEND-REGISTER-CODE-TO-USER',['code' => $code]);
            return response([
                'message' => 'کد مجددا ارسال گردید !'
            ],200);
        }
        throw new ModelNotFoundException('کاربری یافت نشد یا قبلا فعالسازی شده است !');
    }
}
