<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModeratorChangePasswordRequest;
use App\Services\AuthService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

/**
 * Class ModeratorDashboardController
 * @package App\Http\Controllers
 */
class ModeratorDashboardController extends Controller
{
    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * UserAuthController constructor.
     * @param AuthService $authService
     */
    function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function dashboard()
    {
        if (!auth()->user()->password_generated) {
            return redirect('/moderator/change-password');
        }
        $pageTitle = 'Dashboard';
        return view('moderator.dashboard',compact('pageTitle'));
    }

    /**
     * @return Application|Factory|View
     */
    public function changePassword()
    {
        return view('moderator.change-password');
    }

    /**
     * @param ModeratorChangePasswordRequest $request
     */
    public function postChangePassword(ModeratorChangePasswordRequest $request)
    {
        $return = $this->authService->moderatorChangePassword($request);
        if($return ['success']){
            return redirect('/index')->with(['message'=>$return['message'],'class'=>'alert-success']);
        }else{
            return redirect()->back()->with(['message'=>$return ['message'],'class'=>'alert-danger']);
        }
    }
}
