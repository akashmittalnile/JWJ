<?php

namespace App\Http\Controllers;

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
            return view('pages.admin.revenue.plan');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
