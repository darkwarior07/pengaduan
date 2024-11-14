<?php

namespace App\Http\Controllers;


class LocalizationControler extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($language = 'en')
    {
        request()->session()->put('locale', $language);
        return redirect()->back();
    }
}
