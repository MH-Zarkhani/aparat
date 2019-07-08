<?php

if (!function_exists('toValidateMobileNumber')){
    /**
     * add +98 to mobile number
     * @param $mobile
     * @return string
     */
    function toValidateMobileNumber($mobile) {

        return $mobile ='+98' . substr($mobile , -10 , 10);
    }
}

if (!function_exists('randomVerificationCode')){
    /**
     * create random int
     * @return int
     * @throws Exception
     */
    function randomVerificationCode() {
        return random_int(111111,999999);
    }
}
