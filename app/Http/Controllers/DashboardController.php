<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\CommunityFollower;
use App\Models\PaymentDetail;
use Illuminate\Support\Facades\DB;
use App\Models\Plan;
use App\Models\Rating;
use App\Models\User;
use App\Models\UserPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to show dashboard screen along with data
    public function dashboard()
    {
        try {
            $userCount = User::where('role', 1)->whereIn('status', [1, 2])->count();
            $communityCount = Community::whereIn('status', [1, 2])->count();
            $communityFollowCount = CommunityFollower::count();
            $monthReceived = UserPlan::where('plan_timeperiod', 1)->sum('price');
            $yearReceived = UserPlan::where('plan_timeperiod', 2)->sum('price'); 
            $oneReceived = UserPlan::where('plan_timeperiod', 3)->sum('price'); 
            // $subscribeUserCount = UserPlan::distinct('user_id')->count();
            // $plans = Plan::where('monthly_price', '!=', 0)->select(DB::raw("(SELECT SUM(price) FROM user_plans WHERE user_plans.plan_id = plan.id) as total_amt"), 'plan.name', 'plan.id')->get();

            $plan = Plan::select(DB::raw("(SELECT SUM(price) FROM user_plans WHERE user_plans.plan_id = plan.id) as total_amt, (SELECT COUNT(DISTINCT user_id) FROM user_plans WHERE user_plans.plan_id = plan.id) as total_count"), 'plan.name', 'plan.id')->where('status', 1)->get();

            $data1 = UserPlan::select(DB::raw('sum(price) as y'), DB::raw("DATE_FORMAT(created_at,'%m') as x"))->whereYear('created_at', date('Y'))->where('plan_timeperiod', 1)->groupBy('x')->orderByDesc('x')->get()->toArray();
            $data1Graph = graphData($data1);

            $data2 = UserPlan::select(DB::raw('sum(price) as y'), DB::raw("DATE_FORMAT(created_at,'%m') as x"))->whereYear('created_at', date('Y'))->where('plan_timeperiod', 2)->groupBy('x')->orderByDesc('x')->get()->toArray();
            $data2Graph = graphData($data2);

            $data3 = UserPlan::select(DB::raw('sum(price) as y'), DB::raw("DATE_FORMAT(created_at,'%m') as x"))->whereYear('created_at', date('Y'))->where('plan_timeperiod', 3)->groupBy('x')->orderByDesc('x')->get()->toArray();
            $data3Graph = graphData($data3);

            $planc = UserPlan::select(DB::raw('sum(price) as y'), DB::raw("DATE_FORMAT(created_at,'%m') as x"))->where('plan_id', 7)->whereYear('created_at', date('Y'))->groupBy('x')->orderByDesc('x')->get()->toArray();
            $planb = UserPlan::select(DB::raw('sum(price) as y'), DB::raw("DATE_FORMAT(created_at,'%m') as x"))->where('plan_id', 8)->whereYear('created_at', date('Y'))->groupBy('x')->orderByDesc('x')->get()->toArray();
            $plana = UserPlan::select(DB::raw('sum(price) as y'), DB::raw("DATE_FORMAT(created_at,'%m') as x"))->where('plan_id', 9)->whereYear('created_at', date('Y'))->groupBy('x')->orderByDesc('x')->get()->toArray();
            $plancGraph = graphData($planc);
            $planbGraph = graphData($planb);
            $planaGraph = graphData($plana);

            $rating = Rating::select('rating.id', 'rating.userid', 'rating.rating', 'rating.description', 'rating.status', 'rating.created_at')->orderByDesc('id')->limit(5)->get();

            return view('pages.admin.dashboard')->with(compact('userCount', 'communityCount', 'communityFollowCount', 'yearReceived', 'oneReceived', 'monthReceived', 'plan', 'data1Graph', 'data2Graph', 'data3Graph', 'plancGraph', 'planbGraph', 'planaGraph', 'rating'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the list of rating & review
    public function ratingReviews(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = Rating::join('users as u', 'u.id', '=', 'rating.userid')->select('rating.id', 'rating.userid', 'rating.rating', 'rating.description', 'rating.status', 'rating.created_at', 'u.name');
                if ($request->filled('search')) {
                    $data->whereRaw("(`u`.`name` LIKE '%" . $request->search . "%')");
                }
                if ($request->filled('star')) {
                    $data->where('rating', $request->star);
                } else $data->whereIn('rating', [1, 2, 3, 4, 5]);
                $data = $data->orderByDesc('id')->paginate(config('constant.paginatePerPage'));

                $html = '';
                foreach ($data as $key => $val) {
                    $pageNum = $data->currentPage();
                    $index = ($pageNum == 1) ? ($key + 1) : ($key + 1 + (config('constant.paginatePerPage') * ($pageNum - 1)));
                    $ratingNum = '';
                    for ($i=1; $i<=5; $i++) { 
                        if($i<=$val->rating){
                            $ratingNum .= "<span class='activerating'><i class='las la-star'></i></span>";
                        } else {
                            $ratingNum .= "<span><i class='las la-star'></i></span>";
                        }
                    }
                    $html .= "<tr>
                        <td>
                            <div class='sno'>$index</div>
                        </td>
                        <td>
                            $val->name
                        </td>
                        <td>
                            <div class='review-rating'>
                                <div class='review-rating-icon'>
                                    $ratingNum
                                </div>
                                <div class='review-rating-text'>$val->rating Rating</div>
                            </div>
                        </td>
                        <td style='width:50%;'>
                            $val->description
                        </td>
                        <td>
                            <a class='trash-btn' data-id='$val->id' id='delete-button' href='javacsript:void(0)'><img src='". assets('assets/images/trash.svg') ."'></a>
                        </td>
                    </tr>";
                }

                if ($data->isEmpty())
                    return errorMsg("No ratings found");
                $response = [
                    'currentPage' => $data->currentPage(),
                    'lastPage' => $data->lastPage(),
                    'total' => $data->total(),
                    'html' => $html,
                ];
                return successMsg('Ratings list', $response);
            }
            return view('pages.admin.rating-reviews');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to delete rating
    public function deleteRating(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                Rating::where('id', $request->id)->delete();
                return redirect()->back()->with('success', 'Rating deleted successfully.');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used ---
    public function ratingDownloadReport(Request $request)
    {
        try {
            $data = Rating::join('users as u', 'u.id', '=', 'rating.userid')->select('rating.id', 'rating.userid', 'rating.rating', 'rating.description', 'rating.status', 'rating.created_at', 'u.name');
            if ($request->filled('search')) {
                $data->whereRaw("(`u`.`name` LIKE '%" . $request->search . "%')");
            }
            if ($request->filled('star')) {
                $data->where('rating', $request->star);
            } else $data->whereIn('rating', [1, 2, 3, 4, 5]);
            $data = $data->orderByDesc('id')->get();
            $this->downloadRatingReportFunction($data);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used ---
    public function downloadRatingReportFunction($ratings)
    {
        try {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="Ratings Report ' . time() . '.csv');
            $output = fopen("php://output", "w");
            fputcsv($output, array('S.No.', 'Name', 'Rating', 'Review', 'Reviewed On'));
            if (count($ratings) > 0) {
                foreach ($ratings as $key => $rating) {
                    $final = [
                        $key + 1,
                        $rating->user->name,
                        $rating->rating,
                        $rating->description,
                        date('d M, Y h:iA', strtotime($rating->created_at))
                    ];
                    fputcsv($output, $final);
                }
            }
            fclose($output);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Exception => ' . $e->getMessage(),
            ]);
        }
    }
}
