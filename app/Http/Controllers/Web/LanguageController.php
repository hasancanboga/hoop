<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class LanguageController extends Controller
{
    public function set($language): RedirectResponse
    {
        return redirect()->back()->cookie('locale', $language);
    }
}
