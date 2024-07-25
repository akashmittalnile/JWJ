<?php

use Illuminate\Support\Facades\Mail;
use App\Mail\DefaultMail;
use App\Models\Community;
use App\Models\FirebaseChat;
use App\Models\Journal;
use App\Models\Notify;
use App\Models\Plan;
use App\Models\Post;
use App\Models\Routine;
use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// Dev name : Dishant Gupta
// This function is used to push notification using firebase
if (!function_exists('sendNotification')) {
    function sendNotification($fcm, $data){

        $credentialsFilePath = "fcm.json";
        $client = new \Google_Client();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();
        $access_token = $token['access_token'];
        $data['apiurl'] = 'https://fcm.googleapis.com/v1/projects/'.config('constant.fcm.FCM_PROJECT_ID').'/messages:send';
        $headers = [
            'Authorization: Bearer ' . $access_token,
            'Content-Type:application/json'
        ];
        $data['headers'] = $headers;
        
        $fields = [
            'message' => [
                'token' => $fcm,
                'notification' => [
                    'title' => $data['title'],
                    'body' => $data['msg']
                ]
            ]
        ];
        $fields = json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $data['apiurl']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $data['headers']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_exec($ch);
        $res = curl_close($ch);
        if($res){
            return response()->json([
                'message' => 'Notification has been Sent'
            ]);
        } else {
            return response()->json([
                'message' => 'Notification not Sent'
            ]);
        }
    }
}



// Dev name : Dishant Gupta
// This function is used to encrypt decrypt data
if (!function_exists('encrypt_decrypt')) {
    function encrypt_decrypt($action, $string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'JourneywithJournals';
        $secret_iv = 'JourneywithJournals';
        // hash
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
}

// Dev name : Dishant Gupta
if (!function_exists('successMsg')) {
    function successMsg($msg, $data = [])
    {
        return response()->json(['status' => true, 'message' => $msg, 'data' => $data]);
    }
}

// Dev name : Dishant Gupta
if (!function_exists('errorMsg')) {
    function errorMsg($msg, $data = [])
    {
        return response()->json(['status' => false, 'message' => $msg, 'data' => $data]);
    }
}

// Dev name : Dishant Gupta
if (!function_exists('isInvalidExtension')) {
    function isInvalidExtension($files)
    {
        $extensions = ['jpg', 'png', 'jpeg'];
        $return = 0;
        if (count($files) > 0) {
            foreach ($files as $key => $file) {
                $value = $file->extension();
                if(!in_array(strtolower($value), $extensions)) $return+=1;
            }
        } else return false;
        if($return == 0) return false; 
        else return true;
    }
}

// Dev name : Dishant Gupta
// This function is used to change the existing function asset because of its path
if (!function_exists('assets')) {
    function assets($path)
    {
        return asset('public/' . $path);
    }
}

// Dev name : Dishant Gupta
// This function is used to send mail
if (!function_exists('sendEmail')) {
    function sendEmail($data)
    {
        $data['from_email'] = config('constant.mailFromAddress');
        Mail::to($data['to_email'])->send(new DefaultMail($data));
    }
}

// Dev name : Dishant Gupta
// This function is used to send mail by PHPMailer
if (!function_exists('sendMail')) {
    function sendMail($data)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://niletechinnovations.com/projects/journey/demo/testmail.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(),
        ));
        $create_response = curl_exec($curl);
        curl_close($curl);
    }
}

// Dev name : Dishant Gupta
// This function is used to check email address is exist or not 
if (!function_exists('emailExist')) {
    function emailExist($email)
    {
        $exist = User::where('email', $email)->whereIn('status', [0, 1, 2, 3])->first();
        if (isset($exist->id)) return true;
        else false;
    }
}

// Dev name : Dishant Gupta
// This function is used to getting the date of month
if (!function_exists('getMonthDate')) {
    function getMonthDate($month, $year)
    {
        if ($month == "02" || $month == "2") {
            if ($year % 4 == 0) return 29;
            else return 28;
        } else if (($month=="01"||$month=="1") || ($month=="03"||$month=="3") || ($month=="05"||$month=="5") || ($month=="07"||$month=="7") || ($month=="08"||$month=="8") || $month == "10" || $month == "12") return 31;
        else return 30;
    }
}

// Dev name : Dishant Gupta
// This function is used to save a file
if (!function_exists('fileUpload')) {
    function fileUpload($file, $path)
    {
        $name = $file->getClientOriginalName();
        $file->move(public_path("$path"), $name);
        return $name;
    }
}

// Dev name : Dishant Gupta
// This function is used to remove a file
if (!function_exists('fileRemove')) {
    function fileRemove($path)
    {
        $link = public_path("$path");
        if (File::exists($link)) {
            unlink($link);
        }
    }
}

// Dev name : Dishant Gupta
// This function is used to getting the list of notifications
if (!function_exists('getNotification')) {
    function getNotification($seen = null)
    {
        if($seen=='unseen'){
            $notify = Notify::where('receiver_id', auth()->user()->id)->where('is_seen', 1)->count();
            return $notify;
        } else {
            $notify = Notify::where('receiver_id', auth()->user()->id)->orderByDesc('id')->get();
            return $notify;
        }
    }
}

// Dev name : Dishant Gupta
// This function is used to send the notification to admin from user
if (!function_exists('notifyAdmin')) {
    function notifyAdmin($data)
    {
        $admin = User::where('role', 2)->where('status', 1)->get();
        foreach($admin as $val){
            $notify = new Notify;
            $notify->sender_id = $data['user_id'];
            $notify->receiver_id = $val->id;
            $notify->type = $data['type'];
            $notify->title = $data['title'];
            $notify->message = $data['message'];
            $notify->save();
        }
    }
}

// Dev name : Dishant Gupta
// This function is used to send the notification to users from admin
if (!function_exists('notifyUsers')) {
    function notifyUsers($data)
    {
        $users = User::where('role', 1)->where('status', 1)->get();
        foreach($users as $val){
            $notify = new Notify;
            $notify->sender_id = auth()->user()->id;
            $notify->receiver_id = $val->id;
            $notify->type = $data['type'];
            $notify->image_id = $data['image_id'] ?? null;
            $notify->title = $data['title'];
            $notify->message = $data['message'];
            $notify->save();
            $pushData = array(
                'msg' => $data['message'],
                'title' => $data['title']
            );
            sendNotification($val->fcm_token, $pushData);
        }
    }
}

// Dev name : Dishant Gupta
// This function is used to check message alert for users
if (!function_exists('isAlert')) {
    function isAlert()
    {
        $chat = FirebaseChat::where('unseen_msg_count', '>=', '1')->count();
        if($chat > 0) return true;
        return false;
    }
}

// Dev name : Dishant Gupta
// This function is used to filter data for usage of graphs
if (!function_exists('graphData')) {
    function graphData($data)
    {
        $data_x = collect($data)->pluck('x')->toArray();
        $data_y = collect($data)->pluck('y')->toArray();
        $dataGraph = [];
        for($i = 1; $i <= 12; $i++){
            if(in_array( $i, $data_x )){
                $indx = array_search($i, $data_x);
                $dataGraph[$i-1] = $data_y[$indx];
            }else{
                $dataGraph[$i-1] = 0;
            }
        }
        return $dataGraph;
    }
}

if (!function_exists('journalLimit')) {
    function journalLimit($count = 0)
    {
        $journal = Journal::where('created_by', auth()->user()->id)->whereDate('created_at', date('Y-m-d'))->count();
        $userPlan = UserPlan::where('user_id', auth()->user()->id)->where('status', 1)->first();
        if(isset($userPlan->id)){
            $plan = Plan::where('id', $userPlan->plan_id)->where('status', 1)->first();
            if($journal >= $plan->entries_per_day) return false;
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('journalImageLimit')) {
    function journalImageLimit($count = 0)
    {
        $userPlan = UserPlan::where('user_id', auth()->user()->id)->where('status', 1)->first();
        if(isset($userPlan->id)){
            $plan = Plan::where('id', $userPlan->plan_id)->where('status', 1)->first();
            if($plan->picture_per_day < $count) return false;
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('routineLimit')) {
    function routineLimit()
    {
        $routine = Routine::whereNull('shared_by')->where('created_by', auth()->user()->id)->whereDate('created_at', date('Y-m-d'))->count();
        $userPlan = UserPlan::where('user_id', auth()->user()->id)->where('status', 1)->first();
        if(isset($userPlan->id)){
            $plan = Plan::where('id', $userPlan->plan_id)->where('status', 1)->first();
            if($routine >= $plan->routines) return false;
            return true;
        } else {
            return false;
        }
    }
}
if (!function_exists('communityLimit')) {
    function communityLimit()
    {
        $userPlan = UserPlan::where('user_id', auth()->user()->id)->where('status', 1)->first();
        if(isset($userPlan->id)){
            $plan = Plan::where('id', $userPlan->plan_id)->where('status', 1)->first();
            if($plan->community == 3) return true;
            return false;
        } else {
            return false;
        }
    }
}
if (!function_exists('postLimit')) {
    function postLimit()
    {
        $userPlan = UserPlan::where('user_id', auth()->user()->id)->where('status', 1)->first();
        if(isset($userPlan->id)){
            $plan = Plan::where('id', $userPlan->plan_id)->where('status', 1)->first();
            if($plan->community == 1) return false;
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('numbers')) {
    function numbers($num)
    {
        $num = strval($num);
        $len = strlen($num);
        if($num[$len-1] == 1) return 'st';
        elseif($num[$len-1] == 2) return 'nd';
        elseif($num[$len-1] == 3) return 'rd';
        else return 'th';
    }
}
