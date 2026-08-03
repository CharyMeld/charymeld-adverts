<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advert;

class AdvertController extends Controller
{
    public function dashboard()
    {
        return view('advertiser.dashboard');
    }

    public function show($id)
    {
        $advert = Advert::findOrFail($id);
        return view('ads.show', compact('advert'));
    }

    // stubbed create/store/edit/update/destroy methods would go here
}
