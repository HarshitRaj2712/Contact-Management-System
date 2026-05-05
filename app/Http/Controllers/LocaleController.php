<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(Request $request, $locale)
    {
        if (in_array($locale, ['en','es'])) {
            session(['locale' => $locale]);
            app()->setLocale($locale);
        }
        return back();
    }
}
