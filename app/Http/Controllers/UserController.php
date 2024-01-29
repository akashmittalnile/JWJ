<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function users(Request $request)
    {
        try {
            if($request->ajax()){
                $data = User::where('role', 1)->whereIn('status', [1,2]);
                if($request->filled('search')){
                    $data->where('name', 'like', '%' . $request->search . '%')->orWhere('email', 'like', '%'. $request->search .'%')->orWhere('mobile', 'like', '%'. $request->search .'%');
                }
                $data = $data->orderByDesc('id')->paginate(config('constant.paginatePerPage'));
                
                if($data->total() < 1) return errorMsg("No users found");
                $response = array(
                    'currentPage' => $data->currentPage(),
                    'lastPage' => $data->lastPage(),
                    'total' => $data->total(),
                    'html' => $data,
                );
                return successMsg('Users list', $response);
            }
            return view('pages.admin.user.list');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function userDetails($id)
    {
        try {
            $user = User::where('id', $id)->first();
            return view('pages.admin.user.details')->with(compact('user'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function userChangeStatus(Request $request)
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
                $user = User::where('id', $id)->update([
                    'status'=> $request->status
                ]);
                return successMsg('Status changed successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
