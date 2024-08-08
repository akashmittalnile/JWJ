<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use App\Models\JournalImage;
use App\Models\JournalSearchCriteria;
use App\Models\MoodMaster;
use App\Models\PdfPayment;
use App\Models\SearchCriteria;
use App\Models\User;
use Dompdf\Dompdf;
use Stripe;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\error;

class JournalController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to show the list of all moods for journals like, happy, sad, anger & anxiety
    public function mood() {
        try{
            $mood = MoodMaster::where('status', 1)->get();
            $response = array();
            foreach($mood as $val){
                $temp['id'] = $val->id;
                $temp['name'] = $val->name;
                $temp['logo'] = isset($val->logo) ? assets('assets/images/'.$val->logo) : null;
                $temp['status'] = $val->status;
                $temp['created_at'] = date('d M, Y h:i A', strtotime($val->created_at));
                $temp['updated_at'] = date('d M, Y h:i A', strtotime($val->updated_at));
                $response[] = $temp;
            }
            return successMsg('Moods', $response);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to show the list of search criteria for journals
    public function searchCriteria(Request $request) {
        try{
            $mood = SearchCriteria::where('status', 1);
            if($request->filled('name')) $mood->where('name', 'LIKE', '%'.$request->name.'%');
            $mood = $mood->get();
            $response = array();
            foreach($mood as $val){
                $temp['id'] = $val->id;
                $temp['name'] = $val->name;
                $temp['description'] = $val->description;
                $temp['status'] = $val->status;
                $temp['my_criteria'] = ($val->created_by == auth()->user()->id) ? true : false;
                $temp['created_at'] = date('d M, Y h:i A', strtotime($val->created_at));
                $temp['updated_at'] = date('d M, Y h:i A', strtotime($val->updated_at));
                $response[] = $temp;
            }
            return successMsg('Search Criteria', $response);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to show the list of journals
    public function journal(Request $request) {
        try{
            $journal = Journal::where('journals.created_by', auth()->user()->id);
            if($request->filled('title')) $journal->where('journals.title', 'LIKE', '%'.$request->title.'%');
            if($request->filled('status')) $journal->where('journals.status', $request->status);
            else $journal->where('journals.status', 1);
            if($request->filled('mood_id')) $journal->where('journals.mood_id', $request->mood_id);
            if($request->filled('date')) $journal->whereDate('journals.created_at', $request->date);
            if($request->filled('search_criteria_id')) $journal->whereIn('jsc.search_id', $request->search_criteria_id);
            $journal = $journal->select('journals.*')->orderByDesc('journals.id')->distinct('journals.id')->paginate(config('constant.apiPaginatePerPage'));
            $response = array();
            foreach($journal as $val){
                $imgs = JournalImage::where('journal_id', $val->id)->get();
                $criteria = JournalSearchCriteria::join('search_criteria as sc', 'sc.id', '=', 'journals_search_criteria_mapping.search_id')->where('journal_id', $val->id)->select('journals_search_criteria_mapping.id', 'sc.name')->get();
                $mood = MoodMaster::where('id', $val->mood_id)->first();
                $path = array();
                foreach($imgs as $item){
                    $temp1['id'] = $item->id;
                    $temp1['img_path'] = isset($item->name) ? assets('uploads/journal/'.$item->name) : null;
                    $path[] = $temp1;
                }
                $search = array();
                foreach($criteria as $item){
                    $temp2['id'] = $item->id;
                    $temp2['name'] = $item->name;
                    $search[] = $temp2;
                }
                $temp['id'] = $val->id;
                $temp['title'] = $val->title;
                $temp['content'] = $val->content;
                $temp['status'] = $val->status;
                $temp['mood_id'] = $val->mood_id;
                $temp['mood_name'] = $mood->name;
                $temp['mood_logo'] = isset($mood->logo) ? assets('assets/images/'.$mood->logo) : null;
                $temp['images'] = $path;
                $temp['search_criteria'] = $search;
                $temp['download_pdf'] = null;
                $temp['created_at'] = date('d M, Y h:i A', strtotime($val->created_at));
                $temp['updated_at'] = date('d M, Y h:i A', strtotime($val->updated_at));
                $response[] = $temp;
            }
            $pagination = array(
                'currentPage' => $journal->currentPage(),
                'lastPage' => $journal->lastPage(),
                'total' => $journal->total()
            );
            return successMsg('Journals', ['data' => $response, 'pagination' => $pagination]);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to get the details of particular journal
    public function journalDetails($id) {
        try{
            $journal = Journal::join('journals_search_criteria_mapping as jsc', 'jsc.journal_id', '=', 'journals.id')->where('journals.id', $id)->select('journals.*')->first();
            if(isset($journal->id)){
                $imgs = JournalImage::where('journal_id', $journal->id)->get();
                $criteria = JournalSearchCriteria::join('search_criteria as sc', 'sc.id', '=', 'journals_search_criteria_mapping.search_id')->where('journal_id', $journal->id)->select('journals_search_criteria_mapping.id', 'sc.name')->get();
                $mood = MoodMaster::where('id', $journal->mood_id)->first();
                $path = array();
                foreach($imgs as $item){
                    $temp1['id'] = $item->id;
                    $temp1['img_path'] = isset($item->name) ? assets('uploads/journal/'.$item->name) : null;
                    $path[] = $temp1;
                }
                $search = array();
                foreach($criteria as $item){
                    $temp2['id'] = $item->id;
                    $temp2['name'] = $item->name;
                    $search[] = $temp2;
                }
                $response = [
                    'id' => $journal->id,
                    'title' => $journal->title,
                    'content' => $journal->content,
                    'status' => $journal->status,
                    'mood_id' => $journal->mood_id,
                    'mood_name' => $mood->name,
                    'mood_logo' => isset($mood->logo) ? assets('assets/images/'.$mood->logo) : null,
                    'images' => $path,
                    'search_criteria' => $search,
                    'download_pdf' => url('/').'/api/download-pdf/'.encrypt_decrypt('encrypt', $journal->id),
                    'created_at' => date('d M, Y h:i A', strtotime($journal->created_at)),
                    'updated_at' => date('d M, Y h:i A', strtotime($journal->updated_at)),
                ];
                return successMsg('Journal details', $response);
            } else {
                return errorMsg('Journal not found.');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function buyPdf(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'start_date' => 'required',
                'card_id' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $isAlready = PdfPayment::where('status', 1)->where('user_id', auth()->user()->id)->whereDate('start_date', $request->start_date)->first();
                if(isset($isAlready->id)) return errorMsg('Already purchased');
                $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET"));
                Stripe\Stripe::setApiKey(env("STRIPE_SECRET"));
                $user = User::where('id', auth()->user()->id)->first();
                $paymentIntent = $stripe->paymentIntents->create([
                    'customer' => $user->customer_id,
                    'amount' => 29.99*100,
                    'currency' => 'usd',
                    'payment_method_types' => ['card'],
                    'payment_method' => $request->card_id,
                ]);
                $confirm = $stripe->paymentIntents->confirm(
                    $paymentIntent->id,
                );
                if(isset($confirm->id)){
                    $pdf = new PdfPayment;
                    $pdf->user_id = auth()->user()->id;
                    $pdf->start_date = \Carbon\Carbon::createFromFormat('m-d-Y', $request->start_date)->format('Y-m-d');
                    $pdf->end_date = \Carbon\Carbon::createFromFormat('m-d-Y', $request->start_date)->addMonth(6)->format('Y-m-d');
                    $pdf->amount = 29.99;
                    $pdf->card_id = $request->card_id;
                    $pdf->transaction_id = $confirm->latest_charge;
                    $pdf->payment_date = date('Y-m-d');
                    $pdf->status = 1;
                    $pdf->save();
                    return successMsg('Payment done', url('/').'/api/download-pdf/'.encrypt_decrypt('encrypt', auth()->user()->id).'/'.encrypt_decrypt('encrypt', $request->start_date));
                } else {
                    return errorMsg('Payment failed');
                }
                
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    public function journalPdf(Request $request)
    {
        try{
            $pdf = PdfPayment::where('status', 1)->where('user_id', auth()->user()->id);
            if($request->filled('start_date')) $pdf->whereDate('start_date', $request->start_date);
            $pdf = $pdf->orderByDesc('id')->get();
            $response = array();
            foreach($pdf as $val){
                $temp['start_date'] = date('m-d-Y', strtotime($val->start_date));
                $temp['end_date'] = date('m-d-Y', strtotime($val->end_date));
                $temp['payment_date'] = date('m-d-Y', strtotime($val->payment_date));
                $temp['amount'] = $val->amount;
                $temp['download_url'] = url('/').'/api/download-pdf/'.encrypt_decrypt('encrypt', $val->user_id).'/'.encrypt_decrypt('encrypt', $val->start_date);
                $response[] = $temp;
            }
            return successMsg('Journal pdf', $response);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    public function generatePDF($id, $date)
    {
        $id = encrypt_decrypt('decrypt', $id);
        $date = encrypt_decrypt('decrypt', $date);
        $user = User::where('id', $id)->first();
        $journals = Journal::where('journals.created_by', $id)->whereDate('created_at', '>=', date('Y-m-d', strtotime($date)))->whereDate('created_at', '<=', date('Y-m-d', strtotime("+6 months $date")))->with('searchCriteria', 'images', 'mood', 'user')->get();
        $html = view('pages.admin.journal.pdf', compact('journals', 'date', 'user'))->render();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->stream($user->user_name."_".strval(date('dmYhi'))."_journal.pdf");
    }

    // Dev name : Dishant Gupta
    // This function is used to delete the journal
    public function deleteJournal(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                Journal::where('id', $request->id)->delete();
                JournalSearchCriteria::where('journal_id', $request->id)->delete();
                $img = JournalImage::where('journal_id', $request->id)->get();
                foreach($img as $val){
                    fileRemove("/uploads/journal/" . $val->name);
                }
                JournalImage::where('journal_id', $request->id)->delete();
                return successMsg('Journal deleted successfully.');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to create a journal
    public function createJournal(Request $request) {
        try{
            $count = 0;
            if(isset($request->file) && count($request->file) > 0) $count = count($request->file);
            $journalLimit = journalLimit();
            $imageLimit = journalImageLimit($count);
            if(!$journalLimit['status']) return errorMsg($journalLimit['message']);
            if(!$imageLimit['status']) return errorMsg($imageLimit['message']);
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'content' => 'required',
                'mood_id' => 'required',
                'file' => 'array',
                'file.*' => 'max:5120',
                'criteria' => 'array',
                'new_criteria' => 'array',
            ], [
                'file.*.max' => 'File must not be greater than 5MB',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $newCriteriaArr = array();
                if(isset($request->new_criteria) && count($request->new_criteria)){
                    foreach($request->new_criteria as $vaal){
                        $already = SearchCriteria::where('name', $vaal)->first();
                        if(isset($already->id)) {
                            array_push($newCriteriaArr, $already->id);
                        } else {
                            $criteria = new SearchCriteria;
                            $criteria->name = $vaal ?? null;
                            $criteria->description = $vaal ?? null;
                            $criteria->created_by = auth()->user()->id;
                            $criteria->status = 1;
                            $criteria->created_at =  isset($request->created_at) ? date('Y-m-d H:i:s', strtotime($request->created_at)) : date('Y-m-d H:i:s');
                            $criteria->updated_at =  isset($request->created_at) ? date('Y-m-d H:i:s', strtotime($request->created_at)) : date('Y-m-d H:i:s');
                            $criteria->save();
                            array_push($newCriteriaArr, $criteria->id);
                        }
                    }
                }
                $criteriaArray = $request->criteria;
                if(isset($request->criteria) && count($request->criteria))
                    $criteriaArray = array_merge($criteriaArray, $newCriteriaArr);
                else $criteriaArray = $newCriteriaArr;

                $journal = new Journal;
                $journal->title = $request->title;
                $journal->content = $request->content;
                $journal->mood_id = $request->mood_id;
                $journal->status = 1;
                $journal->created_by = auth()->user()->id;
                $journal->created_at =  isset($request->created_at) ? date('Y-m-d H:i:s', strtotime($request->created_at)) : date('Y-m-d H:i:s');
                $journal->updated_at =  isset($request->created_at) ? date('Y-m-d H:i:s', strtotime($request->created_at)) : date('Y-m-d H:i:s');
                $journal->save();

                if (isset($request->file) && count($request->file) > 0) {
                    foreach ($request->file as $key => $value) {
                        $name = fileUpload($request->file[$key], "/uploads/journal/");
                        $journalImage = new JournalImage;
                        $journalImage->journal_id = $journal->id;
                        $journalImage->name = $name;
                        $journalImage->type = 'image';
                        $journalImage->created_at =  isset($request->created_at) ? date('Y-m-d H:i:s', strtotime($request->created_at)) : date('Y-m-d H:i:s');
                        $journalImage->updated_at =  isset($request->created_at) ? date('Y-m-d H:i:s', strtotime($request->created_at)) : date('Y-m-d H:i:s');
                        $journalImage->save();
                    }
                }

                if(isset($criteriaArray) && count($criteriaArray)){
                    foreach($criteriaArray as $value){
                        $journalCriteria = new JournalSearchCriteria;
                        $journalCriteria->journal_id = $journal->id;
                        $journalCriteria->search_id = $value;
                        $journalCriteria->status = 1;
                        $journalCriteria->created_at =  isset($request->created_at) ? date('Y-m-d H:i:s', strtotime($request->created_at)) : date('Y-m-d H:i:s');
                        $journalCriteria->updated_at =  isset($request->created_at) ? date('Y-m-d H:i:s', strtotime($request->created_at)) : date('Y-m-d H:i:s');
                        $journalCriteria->save();
                    }
                }
                return successMsg('New journal created successfully.', $journal);
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to update a journal
    public function editJournal(Request $request) {
        try{
            $count = JournalImage::where('journal_id', $request->id)->count();
            $newCount = 0;
            if(isset($request->file) && count($request->file) > 0) $newCount = count($request->file);
            $delCount = 0;
            if(isset($request->deletefile) && count($request->deletefile) > 0) $delCount = count($request->deletefile);
            $imageLimit = journalImageLimit($count+$newCount-$delCount);
            if(!$imageLimit['status']) return errorMsg($imageLimit['message']);
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'title' => 'required',
                'content' => 'required',
                'mood_id' => 'required',
                'file' => 'array',
                'file.*' => 'max:5120',
                'deletefile' => 'array',
                'criteria' => 'array',
                'new_criteria' => 'array',
                'deletecriteria' => 'array',
            ], [
                'file.*.max' => 'File must not be greater than 5MB',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $journal = Journal::where('created_by', auth()->user()->id)->where('id', $request->id)->first();
                $journal->title = $request->title;
                $journal->content = $request->content;
                $journal->mood_id = $request->mood_id;
                $journal->updated_at = date('Y-m-d H:i:s');
                $journal->save();

                if(isset($request->deletefile) && count($request->deletefile) > 0){
                    foreach($request->deletefile as $val){
                        $journalImage = JournalImage::where('id', $val)->where('journal_id', $journal->id)->first();
                        if(isset($journalImage->name)){
                            fileRemove("/uploads/journal/$journalImage->name");
                        }
                        JournalImage::where('id', $val)->where('journal_id', $journal->id)->delete();
                    }
                }
                if (isset($request->file) && count($request->file) > 0) {
                    foreach ($request->file as $key => $value) {
                        $name = fileUpload($request->file[$key], "/uploads/journal/");
                        $journalImage = new JournalImage;
                        $journalImage->journal_id = $journal->id;
                        $journalImage->name = $name;
                        $journalImage->type = 'image';
                        $journalImage->save();
                    }
                }

                if(isset($request->deletecriteria) && count($request->deletecriteria) > 0){
                    foreach($request->deletecriteria as $val){
                        $journalImage = JournalSearchCriteria::where('id', $val)->where('journal_id', $journal->id)->delete();
                    }
                }

                $newCriteriaArr = array();
                if(isset($request->new_criteria) && count($request->new_criteria)){
                    foreach($request->new_criteria as $vaal){
                        $already = SearchCriteria::where('name', $vaal)->first();
                        if(isset($already->id)) {
                            array_push($newCriteriaArr, $already->id);
                        } else {
                            $criteria = new SearchCriteria;
                            $criteria->name = $vaal ?? null;
                            $criteria->description = $vaal ?? null;
                            $criteria->created_by = auth()->user()->id;
                            $criteria->status = 1;
                            $criteria->save();
                            array_push($newCriteriaArr, $criteria->id);
                        }
                    }
                }
                $criteriaArray = $request->criteria;
                if(isset($request->criteria) && count($request->criteria))
                    $criteriaArray = array_merge($criteriaArray, $newCriteriaArr);
                else $criteriaArray = $newCriteriaArr;
                
                if(isset($criteriaArray) && count($criteriaArray)){
                    foreach($criteriaArray as $value){
                        $journalCriteria = new JournalSearchCriteria;
                        $journalCriteria->journal_id = $journal->id;
                        $journalCriteria->search_id = $value;
                        $journalCriteria->status = 1;
                        $journalCriteria->save();
                    }
                }

                return successMsg('Journal updated successfully.', $journal);
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to create a criteria by user
    public function createCriteria(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $criteria = new SearchCriteria;
                $criteria->name = $request->name ?? null;
                $criteria->description = $request->description ?? null;
                $criteria->created_by = auth()->user()->id;
                $criteria->status = 1;
                $criteria->save();
                return successMsg('New criteria created successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to edit the criteria
    public function editCriteria(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required',
                'description' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $criteria = SearchCriteria::where('id', $request->id)->first();
                if(isset($criteria->id)){
                    if($criteria->created_by == auth()->user()->id){
                        $criteria->name = $request->name ?? null;
                        $criteria->description = $request->description ?? null;
                        $criteria->updated_at = date('Y-m-d H:i:s');
                        $criteria->save();
                        return successMsg('Criteria updated successfully');
                    } else return errorMsg('This criteria is not created by you');
                } else return errorMsg('Criteria not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to delete the criteria
    public function deleteCriteria(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $criteria = SearchCriteria::where('id', $request->id)->first();
                if(isset($criteria->id)){
                    if($criteria->created_by == auth()->user()->id){
                        SearchCriteria::where('id', $request->id)->delete();
                        JournalSearchCriteria::where('search_id', $request->id)->delete();
                        return successMsg('Criteria deleted successfully');
                    } else return errorMsg('This criteria is not created by you');
                } else return errorMsg('Criteria not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
