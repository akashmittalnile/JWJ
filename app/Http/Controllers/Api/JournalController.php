<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use App\Models\JournalImage;
use App\Models\JournalSearchCriteria;
use App\Models\MoodMaster;
use App\Models\SearchCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            if($request->filled('mood_id')) $journal->where('journals.mood_id', $request->mood_id);
            if($request->filled('date')) $journal->whereDate('journals.created_at', $request->date);
            if($request->filled('search_criteria_id')) $journal->whereIn('jsc.search_id', $request->search_criteria_id);
            $journal = $journal->select('journals.*')->orderByDesc('journals.id')->distinct('journals.id')->get();
            $response = array();
            foreach($journal as $val){
                $imgs = JournalImage::where('journal_id', $val->id)->get();
                $criteria = JournalSearchCriteria::join('search_criteria as sc', 'sc.id', '=', 'journals_search_criteria_mapping.search_id')->where('journal_id', $val->id)->select('sc.id', 'sc.name')->get();
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
                $temp['created_at'] = date('d M, Y h:i A', strtotime($val->created_at));
                $temp['updated_at'] = date('d M, Y h:i A', strtotime($val->updated_at));
                $response[] = $temp;
            }
            return successMsg('Journals', $response);
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
                $criteria = JournalSearchCriteria::join('search_criteria as sc', 'sc.id', '=', 'journals_search_criteria_mapping.search_id')->where('journal_id', $journal->id)->select('sc.id', 'sc.name')->get();
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
                    $link = public_path() . "/uploads/journal/" . $val->name;
                    if (file_exists($link)) {
                        unlink($link);
                    }
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
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'content' => 'required',
                'mood_id' => 'required',
                'file' => 'required|array',
                'criteria' => 'required|array',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $journal = new Journal;
                $journal->title = $request->title;
                $journal->content = $request->content;
                $journal->mood_id = $request->mood_id;
                $journal->status = 1;
                $journal->created_by = auth()->user()->id;
                $journal->save();

                if ($request->hasFile("file")) {
                    foreach ($request->file('file') as $value) {
                        $name = "JWJ_" .  time() . rand() . "." . $value->getClientOriginalExtension();
                        $value->move("public/uploads/journal", $name);
                        $journalImage = new JournalImage;
                        $journalImage->journal_id = $journal->id;
                        $journalImage->name = $name;
                        $journalImage->type = 'image';
                        $journalImage->save();
                    }
                }

                if(count($request->criteria)){
                    foreach($request->criteria as $value){
                        $journalCriteria = new JournalSearchCriteria;
                        $journalCriteria->journal_id = $journal->id;
                        $journalCriteria->search_id = $value;
                        $journalCriteria->status = 1;
                        $journalCriteria->save();
                    }
                }
                return successMsg('New journal created successfully.', $journal);
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
