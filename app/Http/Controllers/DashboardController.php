<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\CommunityFollower;
use App\Models\PaymentDetail;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to show dashboard screen along with data
    public function dashboard()
    {
        try {
            $userCount = User::where('role', 1)->count();
            $communityCount = Community::count();
            $communityFollowCount = CommunityFollower::count();
            $paymentReceived = PaymentDetail::sum('amount');
            return view('pages.admin.dashboard')->with(compact('userCount', 'communityCount', 'communityFollowCount', 'paymentReceived'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function ratingReviews()
    {
        try {
            return view('pages.admin.rating-reviews');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
