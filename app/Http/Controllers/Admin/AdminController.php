<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Dashboard metrics, pending adverts, transactions
        return view('admin.dashboard');
    }
}
