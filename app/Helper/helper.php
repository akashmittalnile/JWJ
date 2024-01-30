<?php

use Illuminate\Support\Facades\Mail;
use App\Mail\DefaultMail;
use App\Models\User;

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
// This function is used to change the existing function asset because of its path
if (!function_exists('assets')) {
    function assets($path)
    {
        return asset('public/'.$path);
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
// This function is used to check email address is exist or not 
if (!function_exists('emailExist')) {
    function emailExist($email)
    {
        $exist = User::where('email', $email)->whereIn('status', [0,1,2,3])->first();
        if(isset($exist->id)) return true;
        else false;
    }
}

?>