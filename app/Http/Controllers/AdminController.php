<?php

namespace App\Http\Controllers;

use App\User;
use App\UserLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    function index() {
        $entry = DB::table('users')
            ->join('user_levels', 'users.username', '=', 'user_levels.username')
            ->select('users.name', 'users.username', 'users.user_type', 'users.status', 'user_levels.current_level', 'user_levels.coins')
            ->get();
        return view('admin', ['entry' => $entry]);
    }

    function viewProfile($username) {
        $user_entry = DB::table('users')->where('username', $username)->first();
        $user_level_entry = DB::table('user_levels')->where('username', $username)->first();
        $solved_question_entry = DB::table('solved_question_stats')->where('username', $username)->get();
        $attempted_answers = DB::table('attempted_answers')->where('username', $username)->get();
        return view('profile', ['admin' => True, 'user_entry' => $user_entry, 'user_level_entry' => $user_level_entry, 'solved_question_entry' => $solved_question_entry, 'attempted_answers' => $attempted_answers]);
    }

    function coinsGiveaway($username, Request $request) {
        $user_level = UserLevel::where('username', $username)->first();
        $coins = $user_level->coins;
        $coins_inc = $request->get('num_coins');
        $user_level->update(array('coins' => $coins + $coins_inc));
        LogsController::logData('Coins Giveaway', 'Giveaway '.$coins_inc.' coins to '.$username);
        return back();
    }

    function coinsGiveawayAll(Request $request) {
        $coins_inc = $request->get('num_coins');
        $user_level = UserLevel::query()->get();
        foreach($user_level as $row) {
            $coins = $row->coins;
            $row->coins = $coins + $coins_inc;
            $row->save();
        }
        LogsController::logData('Coins Giveaway All', 'Giveaway '.$coins_inc.' coins to all players');
        return back();
    }

    function changeUserType($username, Request $request) {
        if($username != Auth::user()->username) {
            $user_type = $request->get('user_type');
            User::where('username', $username)->first()->update(array('user_type' => $user_type));
            LogsController::logData('Change User Type', 'Chnaged user type of '.$username.' to '.$user_type);
            return back();
        }
        else {
            return view('errors.change_user_type');
        }
    }

    function blockUser($username) {
        if($username != Auth::user()->username) {
            $status = User::where('username', $username)->first()->status;
            if ($status == "active") {
                User::where('username', $username)->first()->update(array('status' => 'blocked'));
                LogsController::logData('Block User Account', 'Blocked user account of '.$username);
            }
            elseif ($status == "blocked") {
                User::where('username', $username)->first()->update(array('status' => 'active'));
                LogsController::logData('Activate User Account', 'Activated user account of '.$username);
            }
        }
        else {
            return "<h1>Cannot block current user <br> Login as another admin and try again </h1>";
        }
        return back();
    }
}
