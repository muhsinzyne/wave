<?php
namespace Wave\Http\Controllers;

use App\Jobs\CreatePosApp;
use App\Models\UserApps;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activeUser  = $this->activeUser();
        $userAppList = UserApps::getUserApps($activeUser->id);

        $item = UserApps::find(2);
        dispatch(new CreatePosApp($item))->delay(Carbon::now()->addSeconds(1));

        return view('theme::dashboard.index', compact('userAppList'));
    }
}
