<?php

namespace App\Services;

use App\Exceptions\SmsException;

class SmsService
{
    /**
     * @throws SmsException
     */
    public function send(string $phone, string $data)
    {
        logger("sending '$data' to '$phone'");

        if ($phone === "123456789") {
            throw new SmsException();
        }
    }
}
