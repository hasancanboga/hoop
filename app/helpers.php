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
 * shorthand for response(["message" => "foo"]);
 */
// function message($text, $code = 200, $extras = [])
// {   
//     $extras['message'] = $text;
//     return response($extras, $code);
// }
