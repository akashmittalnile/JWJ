<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to view a chat screen
    public function chat(Request $request)
    {
        try {
            $user = User::where('status', 1)->where('role', 1)->get();
            return view('pages.admin.support.chat')->with(compact('user'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
