<?php

use Illuminate\Support\Facades\Mail;
use App\Mail\DefaultMail;
use App\Models\Notify;
use App\Models\User;
use Illuminate\Support\Facades\File;

// Dev name : Dishant Gupta
// This function is used to push notification using firebase
if (!function_exists('sendNotification')) {
    function sendNotification($token, $data)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = env('FIREBASE_SERVER_KEY'); // ADD SERVER KEY HERE PROVIDED BY FCM
        $msg = array(
            'body'  => $data['msg'] ?? 'NA',
            'title' => $data['title'] ?? "Journey with Journals",
            'icon'  => "{{ assets('assets/images/logo.svg') }}", //Default Icon
            'sound' => 'default'
        );
        $arr = array(
            'to' => $token,
            'notification' => $msg,
            'data' => $data,
            "priority" => "high"
        );
        $encodedData = json_encode($arr);
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
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
        $data['from_email'] = env('MAIL_FROM_ADDRESS');
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
            $notify = Notify::where('receiver_id', auth()->user()->id)->get();
            return $notify;
        }
        
    }
}
