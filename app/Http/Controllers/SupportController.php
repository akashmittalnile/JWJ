<?php

namespace App\Http\Controllers;

use App\Models\HelpSupport;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function supportCommunication(Request $request)
    {
        try {
            if($request->ajax()){
                $data = HelpSupport::leftJoin('users as u', 'u.id', '=', 'help_and_supports.user_id')->select('help_and_supports.*');
                if($request->filled('search')){
                    $data->whereRaw("(`u`.`name` LIKE '%" . $request->search . "%' or `u`.`email` LIKE '%" . $request->search . "%' or `u`.`mobile` LIKE '%" . $request->search . "%')");
                }
                if($request->filled('status')){
                    $data->where('help_and_supports.status', $request->status);
                };
                if($request->filled('inquiry')){
                    $data->where('help_and_supports.inquiry_type', $request->inquiry);
                };
                $data = $data->orderByDesc('help_and_supports.id')->paginate(config('constant.communityPerPage'));
                if($data->total() < 1) return errorMsg("No users found");

                $html = '';
                foreach($data as $key => $val)
                {
                    $pageNum = $data->currentPage();
                    $index = ($pageNum == 1) ? ($key + 1) : ($key + 1 + (config('constant.paginatePerPage') * ($pageNum - 1)));
                    $phone = isset($val->mobile) ? $val->country_code .' '. $val->mobile : 'NA';
                    $userProfileImage = isset($val->user->profile) ? assets("uploads/profile/$val->user->profile") : assets("assets/images/no-image.jpg");
                    $status = ($val->status == 1) ? 'Active' : 'Inactive';
                    $userName = $val->user->name;
                    $userMobile = $val->user->mobile;
                    $userEmail = $val->user->email;
                    $html .= "<div class='col-md-6'>
                        <div class='jwj-support-card'>
                            <div class='jwjcard-support-head'>
                                <div class='jwjcard-user-card'>
                                    <div class='jwjcard-user-avtar'>
                                        <img src='".$userProfileImage."'>
                                    </div>
                                    <div class='jwjcard-user-text'>
                                        <h4> $userName </h4>
                                    </div>
                                </div>
                                <div class='jwjcard-user-action'>
                                    <a class='phone-btn' href='tel:".$userMobile."'><img src='". assets('assets/images/call.svg') ."'></a>
                                    <a class='email-btn' href='mailto:".$userEmail."'><img src='". assets('assets/images/sms.svg') ."'></a>
                                </div>
                            </div>
                            <div class='jwjcard-support-body'>
                                <div class='support-desc'>
                                    <p>$val->message</p>
                                </div>
                                <div class='support-option-info'>
                                    <p>Inquiry Type</p>
                                    <h2>". config('constant.inquiry_type')[$val->inquiry_type] ."</h2>
                                </div>
                                <div class='support-action-info'>
                                    <div class='row'>
                                        <div class='col-md-4'>
                                            <a class='Sendreply-btn' href='javascript:void(0)'>Send reply</a>
                                        </div>
                                        <div class='col-md-4'>
                                            <select class='form-control'>
                                                <option>Select Status</option>
                                                <option>Closed</option>
                                                <option>In-Progress</option>
                                                <option>Pending</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class='jwjcard-support-foot'>
                                <div class='support-date-info'>
                                    <img src='". assets('assets/images/calendar.svg') ."'> Submitted On ". date('d M, Y h:i a', strtotime($val->created_at)) ."
                                </div>
                            </div>
                        </div>
                    </div>";
                }

                $response = array(
                    'currentPage' => $data->currentPage(),
                    'lastPage' => $data->lastPage(),
                    'total' => $data->total(),
                    'html' => $html,
                );
                return successMsg('Help & Support', $response);
            }
            return view('pages.admin.support.support');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function notification()
    {
        try {
            return view('pages.admin.support.notification');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
