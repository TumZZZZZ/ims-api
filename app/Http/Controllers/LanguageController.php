<?php

namespace App\Http\Controllers;

class LanguageController extends Controller
{
    public function switchLanguage($locale)
    {
        session(['app_locale' => $locale]);
        return redirect()->back();
    }
}
