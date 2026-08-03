<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CsrfController extends Controller
{
    /**
     * Get a fresh CSRF token
     */
    public function token()
    {
        return response()->json([
            'token' => csrf_token()
        ]);
    }
}
