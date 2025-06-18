<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\TwoFactorChallengeViewResponse;

class SimpleTwoFactorChallengeViewResponse implements TwoFactorChallengeViewResponse
{
    public function toResponse($request)
    {
        return view('auth.two-factor-challenge');
    }
}
