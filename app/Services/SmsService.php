<?php

namespace App\Services;

use App\Exceptions\SmsException;

class SmsService
{
    public function send(string $phone, string $data) 
    {
        logger("sending '$data' to '$phone'");

        if(false){
            throw new SmsException();
        }
    }
}
