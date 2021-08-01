<?php

use Illuminate\Contracts\Auth\Authenticatable;

function otp(): int
{
    return app()->environment(['local', 'staging']) ? 1234 : rand(1000, 9999);
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

function getExecutionTime($loopCount, $function): float
{
    $start = getCurrentMillis();
    for ($i = 0; $i < $loopCount; $i++) {
        $function();
    }
    $end = getCurrentMillis();
    dump($end - $start);
    return $end - $start;
}

function getCurrentMillis(): float
{
    return round(microtime(true) * 1000);
}
