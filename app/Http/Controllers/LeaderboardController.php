<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;


class LeaderboardController extends Controller
{
    // public function index(Request $request)
    // {
    //     $filter = $request->query('filter', 'all'); 
    //     $query = User::withCount(['activities as total_points' => function ($query) use ($filter) {
    //         $query->select(DB::raw('SUM(points)'));
    //         if ($filter === 'day') {
    //             $query->whereDate('activity_date', today());
    //         } elseif ($filter === 'month') {
    //             $query->whereMonth('activity_date', now()->month);
    //         } elseif ($filter === 'year') {
    //             $query->whereYear('activity_date', now()->year);
    //         }
    //     }]);
    //     if ($request->has('search') && $request->search) {
    //         $query->where('id', $request->search);
    //     }
    //     $search = $request->search;
    //     $leaderboard = $query->orderByDesc('total_points')->paginate(10);

    //     return view('leaderboard', compact('leaderboard', 'filter','search'));
    // }

    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all'); 

        $users = User::all();
        // dd($users);
        foreach ($users as $user) {
            $user->total_points = $user->activities()->sum('points');
            $user->save();
        }
        $query = User::query();
        if ($filter === 'day') {
            $query->whereHas('activities', function ($query) {
                $query->whereDate('activity_date', today());
            });
        } elseif ($filter === 'month') {
            $query->whereHas('activities', function ($query) {
                $query->whereMonth('activity_date', now()->month);
            });
        } elseif ($filter === 'year') {
            $query->whereHas('activities', function ($query) {
                $query->whereYear('activity_date', now()->year);
            });
        }

        if ($request->has('search') && $request->search) {
            $query->where('id', $request->search);
        }
        $search =  $request->search;

        // $leaderboard = $query->orderByDesc('total_points')->get();
        $leaderboard = $query->orderByDesc('total_points')->paginate(10);
        // dd($leaderboard);
        $rank = 1;
        $previous_points = null;

        $leaderboard = $leaderboard->map(function ($user) use (&$rank, &$previous_points) {
            if ($previous_points !== $user->total_points) {
                $user->rank = $rank;
                $rank++;
            } else {
                $user->rank = $rank - 1;
            }
            $previous_points = $user->total_points;
            return $user;
        });
        
        return view('leaderboard', compact('leaderboard', 'filter', 'search'));
    }

    public function recalculate()
    {
        return redirect()->route('leaderboard.index');
    }
}
