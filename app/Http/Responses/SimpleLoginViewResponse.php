<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginViewResponse;

class SimpleLoginViewResponse implements LoginViewResponse
{
    public function toResponse($request)
    {
        return view('auth.login');
    }
}
