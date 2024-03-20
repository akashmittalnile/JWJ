<?php

namespace App\Http\Controllers;

use App\Models\RoutineCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoutineController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to getting the list of routines category
    public function routineCategory(Request $request)
    {
        try {
            if($request->ajax()){
                $data = RoutineCategory::orderByDesc('id');
                if($request->filled('search')){
                    $data->whereRaw("(`name` LIKE '%" . $request->search . "%')");
                }
                if($request->filled('ustatus')){
                    $data->where('status', $request->ustatus);
                } else $data->whereIn('status', [1,2]);
                $data = $data->paginate(config('constant.paginatePerPage'));
                if($data->total() < 1) return errorMsg("No routine category found");

                $html = '';
                foreach($data as $key => $val)
                {
                    $pageNum = $data->currentPage();
                    $index = ($pageNum == 1) ? ($key + 1) : ($key + 1 + (config('constant.paginatePerPage') * ($pageNum - 1)));
                    $image = isset($val->logo) ? assets("uploads/routine/$val->logo") : assets("assets/images/no-image.jpg");
                    $status = ($val->status == 1) ? 'Active' : 'Inactive';
                    $html .= "<tr>
                    <td>
                        <div class='sno'>$index</div>
                    </td>
                    <td>
                        <img width='150' style='height: 100px; object-fit: cover; object-position: center; border-radius: 5px;' src='".$image."'>
                    </td>
                    <td>
                        $val->name
                    </td>
                    <td>
                        $val->description
                    </td>
                    <td>
                        $status
                    </td>
                    <td>
                        <div class='action-btn-info'>
                            <a class='action-btn dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                                <i class='las la-ellipsis-v'></i>
                            </a>
                            <div class='dropdown-menu'>
                                <a class='dropdown-item view-btn' data-id='".encrypt_decrypt('encrypt', $val->id)."' data-name='$val->name' data-description='$val->description' data-img='$image' data-status='$val->status' id='editComm' href='javascript:void(0)'><i class='las la-edit'></i> &nbsp; Edit</a>
                            </div>
                        </div>
                    </td>
                </tr>";
                }

                $response = array(
                    'currentPage' => $data->currentPage(),
                    'lastPage' => $data->lastPage(),
                    'total' => $data->total(),
                    'html' => $html,
                );
                return successMsg('Routine category list', $response);
            }

            return view('pages.admin.routine.routine-category');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to create a new routine category
    public function routineCategoryStore(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'status' => 'required',
                'file' => 'required|file',
                'description' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                if ($request->hasFile("file")) {
                    $name = fileUpload($request->file, "/uploads/routine/");
                }
                $category = new RoutineCategory;
                $category->name = $request->title;
                $category->code = strtolower(explode(" ", $request->title)[0]) ?? null;
                $category->description = $request->description;
                $category->logo = $name;
                $category->status = $request->status;
                $category->save();
                return successMsg('New category created successfully.');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to update the details of routine category
    public function routineCategoryUpdate(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'status' => 'required',
                'id' => 'required',
                'description' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $id = encrypt_decrypt('decrypt', $request->id);
                $category = RoutineCategory::where('id', $id)->first();
                if ($request->hasFile("file")) {
                    $name = fileUpload($request->file, "/uploads/routine/");
                    if(isset($category->logo)){
                        fileRemove("/uploads/routine/$category->logo");
                    }
                    $category->logo = $name;
                }
                $category->name = $request->title;
                $category->description = $request->description;
                $category->status = $request->status;
                $category->updated_at = date('Y-m-d H:i:s');
                $category->save();
                return successMsg('Category updated successfully.');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to delete the routine category
    public function routineCategoryDelete(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $id = encrypt_decrypt('decrypt', $request->id);
                $category = RoutineCategory::where('id', $id)->first();
                $category->status = -1;
                $category->save();
                return redirect()->back()->with('success', 'Category deleted successfully.');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
