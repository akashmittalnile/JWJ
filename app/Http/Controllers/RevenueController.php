<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    public function revenueManagement()
    {
        try {
            return view('pages.admin.revenue.revenue-management');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function subscriptionPlan()
    {
        try {
            $plan = Plan::get();
            return view('pages.admin.revenue.plan')->with(compact('plan'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
