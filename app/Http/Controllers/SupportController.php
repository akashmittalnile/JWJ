<?php

namespace App\Http\Controllers;

use App\Models\HelpSupport;
use App\Models\Notification;
use App\Models\Notify;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to show the list of all the inquiries for users
    public function supportCommunication(Request $request)
    {
        try {
            if($request->ajax()){
                $data = HelpSupport::select('help_and_supports.*');
                if($request->filled('search')){
                    $data->whereRaw("(`name` LIKE '%" . $request->search . "%' or `email` LIKE '%" . $request->search . "%' or `contact` LIKE '%" . $request->search . "%')");
                }
                if($request->filled('status')){
                    $data->where('help_and_supports.status', $request->status);
                };
                if($request->filled('date')){
                    $request->date = \Carbon\Carbon::createFromFormat('m-d-Y', $request->date)->format('Y-m-d');
                    $data->whereDate('help_and_supports.created_at', $request->date);
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
                    $userProfileImage = (isset($val->user->profile) && File::exists(public_path("uploads/profile/".$val->user->profile)) ) ? assets("uploads/profile/".$val->user->profile) : assets("assets/images/no-image.jpg");

                    $adminReply = '';
                    if(isset($val->past_response) && $val->past_response != ''){
                        $adminReply = "<div class='col-md-4'>
                                            <a class='Sendreply-btn' data-id='". encrypt_decrypt('encrypt', $val->id) ."' data-name='$val->name' data-img='". $userProfileImage ."' data-msg='$val->message' data-time='". date('m-d-Y h:i a', strtotime($val->created_at)) ."' data-updatetime='". date('m-d-Y h:i a', strtotime($val->updated_at)) ."' data-past='$val->past_response' id='seeRply' href='javascript:void(0)'>Your Answer</a>
                                    </div>";
                    }
                    $sendReply = '';
                    if(isset($val->status) && $val->status != 1){
                        $sendReply = "<div class='col-md-4'>
                        <a class='Sendreply-btn' data-id='". encrypt_decrypt('encrypt', $val->id) ."' data-name='$val->name' data-img='". $userProfileImage ."' data-msg='$val->message' data-time='". date('m-d-Y h:i a', strtotime($val->created_at)) ."' id='sendRply' href='javascript:void(0)'>Send reply</a>
                    </div>";
                    }

                    $status = ($val->status == 1) ? 'Closed' : (($val->status == 2) ? 'In-Progress' : 'Pending');

                    $html .= "<div class='col-md-6'>
                        <div class='jwj-support-card'>
                            <div class='jwjcard-support-head'>
                                <div class='jwjcard-user-card'>
                                    <div class='jwjcard-user-avtar'>
                                        <img src='".$userProfileImage."'>
                                    </div>
                                    <div class='jwjcard-user-text'>
                                        <h4>". ucwords($val->name) ."</h4>
                                    </div>
                                </div>
                                <div class='jwjcard-user-action'>
                                    <a class='phone-btn' href='tel:".$val->contact."'><img src='". assets('assets/images/call.svg') ."'></a>
                                    <a class='email-btn' href='mailto:".$val->email."'><img src='". assets('assets/images/sms.svg') ."'></a>
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
                                        $sendReply
                                        $adminReply
                                        <div class='col-md-4'>
                                            <div class='support-option-info'>
                                                <h2 style='border-color: #5cabe6; background: white; padding: 13px 32px;' >". $status ."</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class='jwjcard-support-foot'>
                                <div class='support-date-info'>
                                    <img src='". assets('assets/images/calendar.svg') ."'> Submitted On ". date('m-d-Y h:i a', strtotime($val->created_at)) ."
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

    // Dev name : Dishant Gupta
    // This function is used to add a reply of administrator
    public function sendReply(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required',
                'id' => 'required',
                'message'=>'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $id = encrypt_decrypt('decrypt', $request->id);
                $support = HelpSupport::where('id', $id)->first();
                $support->status = $request->status ?? null;
                $support->past_response = $request->message ?? null;
                $support->updated_at = date('Y-m-d H:i:s');
                $support->save();
                return successMsg('Message sent successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to downlaod a help & support communication
    public function supportDownloadReport(Request $request)
    {
        try {
            $data = HelpSupport::select('help_and_supports.*');
            if($request->filled('search')){
                $data->whereRaw("(`name` LIKE '%" . $request->search . "%' or `email` LIKE '%" . $request->search . "%' or `contact` LIKE '%" . $request->search . "%')");
            }
            if($request->filled('status')){
                $data->where('help_and_supports.status', $request->status);
            };
            if($request->filled('date')){
                $data->whereMonth('help_and_supports.created_at', date('m', strtotime($request->date)))->whereYear('help_and_supports.created_at', date('Y', strtotime($request->date)));
            };
            if($request->filled('inquiry')){
                $data->where('help_and_supports.inquiry_type', $request->inquiry);
            };
            $data = $data->orderByDesc('help_and_supports.id')->get();
            $this->downloadSupportReportFunction($data);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to download the csv file of journey with journals registered users
    public function downloadSupportReportFunction($data)
    {
        try {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="Help and Support List "' . time() . '.csv');
            $output = fopen("php://output", "w");

            fputcsv($output, array('S.No.', 'Name', 'Email ID', 'Contact Number', 'User Query', 'Admin Reply', 'Query Status', 'Query Date', 'Admin Reply Date'));

            if (count($data) > 0) {
                foreach ($data as $key => $row) {

                    $final = [
                        $key + 1,
                        $row->name,
                        $row->email,
                        $row->contact,
                        $row->message,
                        $row->past_response ?? null,
                        ($row->status == 1) ? 'Closed' : (($row->status == 2) ? 'In-Progress' : 'Pending'),
                        date('m-d-Y h:i a', strtotime($row->created_at)),
                        date('m-d-Y h:i a', strtotime($row->updated_at)),
                    ];

                    fputcsv($output, $final);
                }
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
    
    // Dev name : Dishant Gupta
    // This function is used to getting the list of notification
    public function notification(Request $request)
    {
        try {
            $plan = Plan::where('status', 1)->orderByDesc('id')->get();
            if($request->ajax()){
                $data = Notification::orderByDesc('id');
                if($request->filled('search')){
                    $data->whereRaw("(`text` LIKE '%" . $request->search . "%')");
                }
                if($request->filled('date')){
                    $request->date = \Carbon\Carbon::createFromFormat('m-d-Y', $request->date)->format('Y-m-d');
                    $data->whereDate('created_at', $request->date);
                };
                if($request->filled('inquiry')){
                    $data->where('plan_id', $request->inquiry);
                };
                $data = $data->paginate(config('constant.paginatePerPage'));
                if($data->total() < 1) return errorMsg("No notifications found");

                $html = '';
                foreach($data as $key => $val)
                {
                    $pageNum = $data->currentPage();
                    $userProfileImage = (isset($val->user->profile) && File::exists(public_path("uploads/profile/".$val->user->profile))) ? assets("uploads/profile/".$val->user->profile) : assets("assets/images/no-image.jpg");

                    $planName = ($val->plan_id == 100) ? 'All' : $val->plan->name;

                    $html .= "<div class='col-md-12'>
                    <div class='manage-notification-item'>
                        <div class='manage-notification-icon'>
                            <img src='". assets('assets/images/notification-bing.svg') ."'>
                        </div>
                        <div class='manage-notification-content'>
                            <div class='notification-date'>$val->text</div>
                            <div class='notification-descr'>$val->message</p>
                            </div>
                            <div class='notification-tag'>
                                <div class='tags-list'>
                                    <div class='Tags-text'>". $planName ." Users</div>
                                </div>
                            </div>
                            <div class='notification-date'>Pushed on: ". date('m-d-Y h:i a', strtotime($val->created_at)) ."</div>
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
                return successMsg('Notification', $response);
            }
            return view('pages.admin.support.notification')->with(compact('plan'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to create a notification for admin
    public function createNotification(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'text' => 'required',
                'plan' => 'required',
                'message'=>'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $notification = new Notification;
                $notification->text = $request->text;
                $notification->message = $request->message;
                $notification->plan_id = $request->plan;
                $notification->user_id = auth()->user()->id;
                $notification->status = 1;
                $notification->save();
                if(isset($request->plan) && ($request->plan != 100)){
                    $users = User::where('status', 1)->where('role', 1)->where('plan_id', $request->plan)->get();
                } else {
                    $users = User::where('status', 1)->where('role', 1)->get();
                }
                foreach($users as $val){
                    $notify = new Notify;
                    $notify->sender_id = auth()->user()->id;
                    $notify->receiver_id = $val->id;
                    $notify->type = "PUSH_NOTIFICATION";
                    $notify->title = $request->text;
                    $notify->message = $request->message;
                    $notify->save();
                    if(isset($val->fcm_token) && $val->fcm_token != ''){
                        $data = array(
                            'msg' => $request->message,
                            'title' => $request->text
                        );
                        sendNotification($val->fcm_token, $data);
                    }
                }
                return successMsg('Notification sent successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
