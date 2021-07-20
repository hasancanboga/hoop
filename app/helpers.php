<?php

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Generates a one-time password
 *
 * @return int
 */
function otp(): int
{
    return app()->env(['local', 'staging']) ? 1234 : rand(1000, 9999);
}

function current_user(): ?Authenticatable
{
    return auth()->user();
}

/**
 * Shorthand for ["message" => "foo"]
 *
 * @param string $message
 * @param array $extras
 * @return array
 */
function message(string $message, array $extras = []): array
{
    $final = ['message' => $message];
    foreach ($extras as $key => $value) {
        $final[$key] = $value;
    }
    return $final;
}
