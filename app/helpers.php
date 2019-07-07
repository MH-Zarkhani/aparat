<?php

/**
 * add +98 to mobile number
 * @param $mobile
 * @return string
 */
function toValidateMobileNumber($mobile) {

    return $mobile ='+98' . substr($mobile , -10 , 10);
}