<?php
namespace App\Http\Requests\Auth;


trait getRegisterFieldAndValueTrait
{
    public function getFieldName()
    {
        return $this->has('email') ? 'email' : 'mobile';
    }

    public function getFieldValue()
    {
        $field = $this->getFieldName();
        $value = $this->input($field);
        if ($field === 'mobile'){
            $value = toValidateMobileNumber($value);
        }
        return $value;
    }
}