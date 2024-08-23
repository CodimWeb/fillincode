<?php

namespace App\Helpers;

use Illuminate\Http\Exceptions\HttpResponseException;

class UserHelper
{
    public static function checkAuthor(string $user_id)
    {
        if($user_id != auth()->user()->id) {
            throw new HttpResponseException(response()->json([
                'success'   => false,
            ], 403));
        }
    }
}
