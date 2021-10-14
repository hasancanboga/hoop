<?php

use App\Models\User;

Route::get('/test', function () {

    $user = User::find(1);
   dump( $user->can('create_challenges'));
});
