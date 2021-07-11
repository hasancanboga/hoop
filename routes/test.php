<?php

use App\Models\User;

Route::get('/test', function () {
    App::setLocale("en");
    dump(App::currentLocale());
    dump(__('auth.logout'));
});
