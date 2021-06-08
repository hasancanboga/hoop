<?php

/**
 * Generates a one-time password
 * 
 * @return int
 */
function otp()
{
    return env('APP_ENV') == 'local' ? 1234 : rand(1000, 9999);
}
