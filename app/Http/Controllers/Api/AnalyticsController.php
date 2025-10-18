<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdvertAnalytic;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * Track contact click (phone, email, whatsapp)
     */
    public function trackContactClick(Request $request)
    {
        $request->validate([
            'advert_id' => 'required|exists:adverts,id',
            'type' => 'required|in:phone,email,whatsapp',
        ]);

        AdvertAnalytic::recordContactClick($request->advert_id);

        return response()->json(['success' => true]);
    }
}
