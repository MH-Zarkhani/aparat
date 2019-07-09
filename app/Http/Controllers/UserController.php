<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    const  CHANGE_EMAIL_KEY = 'change.email.for.user.';

    public function changeEmail(ChangeEmailRequest $request)
    {
        try{
            $email = $request->email;
            $userId = auth()->id();
            $user = User::whereId($userId)->whereEmail($email)->first();
            if (empty($user)){
                $code = randomVerificationCode();
                $expirationDate = now()->addMinutes(config('auth.change_email_cache_expiration'));
                Cache::put(self::CHANGE_EMAIL_KEY.$userId,compact('email','code'),$expirationDate);
                //TODO:: send email for change user email
                Log::info('SEND-CHANGE-EMAIL-CODE',compact('code'));
                return response([
                    'message' => 'ایمیلی به شما ارسال شد لطفا صندوق ورودی خود را بررسی نمایید !'
                ],200);
            }else {
                return response([
                   'message' => 'لطفا ایمیل جدید وارد نمایید !' 
                ],200);
            }
        }catch (\Exception $e) {
            Log::error($e);
            return response([
                'message' => 'خطایی رخ داده است سرور قادر به ارسال کد فعالسازی نمی باشد'
            ],500);
        }
    }

    public function changeEmailSubmit(ChangeEmailSubmitRequest $request)
    {
        $userId = auth()->id();
        $changeEmailKey = self::CHANGE_EMAIL_KEY.$userId;
        $cache = Cache::get($changeEmailKey);

        if (empty($cache) || $cache['code'] != $request->code){
            return response([
                'message' => 'درخواست نامعتبر است !'
            ],400);
        }
        $user = auth()->user();
        $user->email = $cache['email'];
        $user->save();

        Cache::forget($changeEmailKey);
        return response([
            'message' => 'ایمیل با موفقیت تغییر کرد !'
        ],200);
    }
}
