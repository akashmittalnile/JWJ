<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class JournalController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to show the listing of all journals
    public function journalList(Request $request)
    {
        try {
            if($request->ajax()){
                $data = Journal::join('users as u', 'u.id', '=', 'journals.created_by')->select('journals.*')->orderByDesc('id');
                if($request->filled('search')){
                    $data->whereRaw("(`journals`.`title` LIKE '%" . $request->search . "%' or `u`.`name` LIKE '%" . $request->search . "%' or `u`.`email` LIKE '%" . $request->search . "%' or `u`.`mobile` LIKE '%" . $request->search . "%')");
                }
                if($request->filled('ustatus')){
                    $data->where('journals.status', $request->ustatus);
                } else $data->whereIn('journals.status', [1,2]);

                $data = $data->paginate(config('constant.paginatePerPage'));
                $html = '';
                foreach($data as $val)
                {
                    $userProfileImage = (isset($val->user->profile) && File::exists(public_path('uploads/profile/'.$val->user->profile)) ) ? assets("uploads/profile/".$val->user->profile) : assets("assets/images/no-image.jpg");
                    $imgs = $val->images;
                    $image = $val->images->first();
                    $image_html = "";
                    foreach($imgs as $name){
                        $image_html .= "<div class='item'>
                        <div class='community-media'>
                                <a data-fancybox='' href='".assets("uploads/journal/$name->name")."'>
                                    <img src='".assets("uploads/journal/$name->name")."'>
                                </a>
                            </div>
                        </div>";
                    }
                    if($image_html == "") {
                        $image_html = "<div class='item'>
                        <div class='community-media'>
                                <img src='".assets('assets/images/no-image.jpg')."'>
                            </div>
                        </div>";
                    }

                    $criteria = $val->searchCriteria;
                    $criteria_html = "";
                    foreach($criteria as $value){
                        $criteria_html .= "<div class='admincommunity-text' style='margin-right: 5px;'>".$value->criteria->name."</div>";
                    }

                    $checked = ($val->status==1) ? 'checked' : '';
                    $journalImg = (isset($image->name) && File::exists(public_path("uploads/journal/".$image->name)) ) ? assets("uploads/journal/".$image->name) : assets("assets/images/no-image.jpg");
                    $html .= "<div class='col-md-6'>
                    <div class='jwj-community-card'>
                        <div class='jwjcard-head'>
                            <div class='jwjcard-group-card'>
                                <div class='jwjcard-group-avtar'>
                                    <img src='".$journalImg."'>
                                </div>
                                <div class='jwjcard-group-text'>
                                    <h4 class='text-capitalize'>".$val->title."</h4>
                                </div>
                            </div>
                            <div class='jwjcard-group-action'>
                                <a class='managecommunity-btn' href='".route('admin.journal.details', encrypt_decrypt('encrypt', $val->id))."'>Manage Journal</a>
                            </div>
                        </div>
                        <div class='jwjcard-body'>
                            $criteria_html

                            <div id='communitycarousel' class='communitycarousels owl-carousel owl-theme'>
                                $image_html
                            </div>

                            <div class='row'>
                                <div class='col-md-6'>
                                    <div class='User-contact-info'>
                                        <div class='User-contact-info-content'>
                                            <h2>Status</h2>
                                            <div class='switch-toggle'>
                                                <p style='color: #8C9AA1;'>Inactive</p>
                                                <div class=''>
                                                    <label class='toggle' for='myToggle".$val->id."'>
                                                        <input data-id='".encrypt_decrypt('encrypt', $val->id)."' class='toggle__input' name='status".$val->id."' $checked type='checkbox' id='myToggle".$val->id."'>
                                                        <div class='toggle__fill'></div>
                                                    </label>
                                                </div>
                                                <p style='color: #8C9AA1;'>Active</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-6'>
                                    <div class='service-shift-card'>
                                        <div class='service-shift-card-image'>
                                            <img src='".assets('assets/images/calendar.svg')."' height='14px'>
                                        </div>
                                        <div class='service-shift-card-text'>
                                            <h2>Submitted On </h2>
                                            <p>".date('m-d-Y h:iA', strtotime($val->created_at))."</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class='jwjcard-foot'>
                            <div class='jwjcard-group-card'>
                                <div class='jwjcard-group-avtar'>
                                    <img src='".$userProfileImage."'>
                                </div>
                                <div class='jwjcard-group-text'>
                                    <h4 class='text-capitalize'>".$val->user->name."</h4>
                                </div>
                            </div>
                            <div class='jwjcard-group-card'>
                                <div class='jwjcard-group-avtar'>
                                    <img src='".assets('assets/images/'.$val->mood->logo)."'>
                                </div>
                                <div class='jwjcard-group-text'>
                                    <h4 class='text-capitalize'>".$val->mood->name."</h4>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>";
                }
                
                if($data->total() < 1) return errorMsg("No journals found");
                $response = array(
                    'currentPage' => $data->currentPage(),
                    'lastPage' => $data->lastPage(),
                    'total' => $data->total(),
                    'html' => $html,
                );
                return successMsg('Journal list', $response);
            }
            return view('pages.admin.journal.list');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to change the status of journal like active, inactive & reject
    public function journalChangeStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $id = encrypt_decrypt('decrypt', $request->id);
                $user = Journal::where('id', $id)->update([
                    'status'=> $request->status,
                ]);
                return successMsg('Status changed successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the data of particular journal
    public function journalDetails(Request $request, $id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            $data = Journal::join('users as u', 'u.id', '=', 'journals.created_by')->select('journals.*', 'u.role', 'u.name as user_name', 'u.profile as user_image')->where('journals.id', $id)->first();
            return view('pages.admin.journal.details')->with(compact('data'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
