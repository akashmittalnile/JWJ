<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\CommunityFollower;
use App\Models\PaymentDetail;
use App\Models\Rating;
use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to show dashboard screen along with data
    public function dashboard()
    {
        try {
            $userCount = User::where('role', 1)->whereIn('status', [1,2])->count();
            $communityCount = Community::whereIn('status', [1,2])->count();
            $communityFollowCount = CommunityFollower::count();
            $paymentReceived = PaymentDetail::sum('amount');
            $subscribeUserCount = UserPlan::distinct('user_id')->count();
            return view('pages.admin.dashboard')->with(compact('userCount', 'communityCount', 'communityFollowCount', 'paymentReceived', 'subscribeUserCount'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Bodheesh vc
    // This function is used ---
    public function ratingReviews(Request $request)
    {
        try {
            if ($request->ajax()) {
                // $query = Rating::leftJoin('users', 'users.id', 'rating.userid');
                $query = Rating::select('rating.id', 'rating.userid', 'rating.rating', 'rating.description', 'rating.status', 'rating.created_at', 'users.name')->Join('users', 'users.id', 'rating.userid');
                // Apply search filters
                if ($request->filled('search')) {
                    $searchTerm = $request->search;
                    $query->where(function ($subquery) use ($searchTerm) {
                        $subquery->where('description', 'like', '%' . $searchTerm . '%')
                            ->orWhere('name', 'like', '%' . $searchTerm . '%');
                    });
                }
                // Apply rating filter
                if ($request->filled('rating')) {
                    $ratingTerm = $request->rating;
                    $query->where('rating', $ratingTerm);
                }
                // Paginate the results
                $ratings = $query->paginate(config('constant.paginatePerPage'));
                // Check if any ratings are found
                if ($ratings->isEmpty())
                    return errorMsg("No ratings found");
                $response = [
                    'currentPage' => $ratings->currentPage(),
                    'lastPage' => $ratings->lastPage(),
                    'total' => $ratings->total(),
                    'html' => $ratings,
                ];
                return successMsg('Ratings list', $response);
            }
            // If not an AJAX request, return the view
            return view('pages.admin.rating-reviews');
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Exception => ' . $e->getMessage(),
            ]);
        }
    }

    // Dev name : Bodheesh vc
    // This function is used ---
    public function deleteRating(Request $request)
    {
        try {
            if ($request->ajax()) {
                // Check if ratingId is present and not empty
                if ($request->has('ratingId') && $request->ratingId !== '') {
                    $ratingId = $request->ratingId;
                    // dd($ratingId);
                    // Load the rating by ID
                    $rating = Rating::findOrFail($ratingId);
                    // Delete the rating
                    $rating->delete();
                    // Return success response
                    return response()->json([
                        'status' => true,
                        'message' => 'Rating deleted successfully.'
                    ]);
                } else {
                    // Return error if ratingId is missing
                    return response()->json([
                        'status' => false,
                        'error' => 'Rating ID is missing in the request.',
                    ]);
                }
            } else {
                // Return error if not an AJAX request
                return response()->json([
                    'status' => false,
                    'error' => 'This endpoint only accepts AJAX requests.',
                ]);
            }
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            \Log::error('Error deleting rating: ' . $e->getMessage());
            // Return error response
            return response()->json([
                'status' => false,
                'error' => 'Error deleting rating: ' . $e->getMessage(),
            ]);
        }
    }

    // Dev name : Bodheesh vc
    // This function is used ---
    public function ratingDownloadReport(Request $request)
    {
        try {
            $ratings = Rating::select('id', 'userid', 'rating', 'description', 'status')->with('user')->get();
            $this->downloadRatingReportFunction($ratings);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Exception => ' . $e->getMessage(),
            ]);
        }
    }

    // Dev name : Bodheesh vc
    // This function is used ---
    public function downloadRatingReportFunction($ratings)
    {
        try {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="Ratings Report ' . time() . '.csv');
            $output = fopen("php://output", "w");
            fputcsv($output, array('S.No.', 'User Name', 'Rating', 'Review', 'Status'));
            if (count($ratings) > 0) {
                foreach ($ratings as $key => $rating) {
                    $final = [
                        $key + 1,
                        $rating->user->name,
                        $rating->rating,
                        $rating->description,
                        $rating->status,
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
