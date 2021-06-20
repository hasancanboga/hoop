<?php

/**
 * Generates a one-time password
 * 
 * @return int
 */
function otp()
{
    return config('app.env') === 'local' ? 1234 : rand(1000, 9999);
}

/**
 * Shorthand for ["message" => "foo"]
 * 
 * @param string $message
 * @return array
 */
function message($message, $extras = [])
{
    $final = ['message' => $message];
    foreach ($extras as $key => $value) {
        $final[$key] = $value;
    }
    return $final;
}
