<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LanguageController extends Controller
{
    public function set($language)
    {
        return redirect()->back()->cookie('locale', $language);
    }
}
