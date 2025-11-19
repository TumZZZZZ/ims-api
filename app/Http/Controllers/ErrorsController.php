<?php

namespace App\Http\Controllers;

class ErrorsController extends BaseApi
{
    public function unauthorized()
    {
        return view('errors.401');
    }

    public function forbidden()
    {
        return view('errors.403');
    }

    public function pageNotFound()
    {
        return view('errors.404');
    }
}
