<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function communityManagement()
    {
        try {
            return view('pages.admin.community.community-management');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function communityManagementDetails($id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            return view('pages.admin.community.details');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function communityApproval()
    {
        try {
            return view('pages.admin.community.approval');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function communityApprovalDetails($id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            return view('pages.admin.community.approval-details');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function communityPostDetails($id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            return view('pages.admin.community.post-details');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
