<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModeratorChangePasswordRequest;
use App\Services\AuthService;
use App\Services\ModeratorService;
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
    protected $authService,$moderatorService;

    /**
     * UserAuthController constructor.
     * @param AuthService $authService
     */
    function __construct(AuthService $authService,ModeratorService $moderatorService)
    {
        $this->authService = $authService;
        $this->moderatorService = $moderatorService;
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
        $getCounts = $this->moderatorService->getDashboardCounts();
        $latestStudents = $this->moderatorService->getLatestUsers('STUDENT');
        $latestProfessors = $this->moderatorService->getLatestUsers('PROFESSOR');
        $latestNotes = $this->moderatorService->getLatestNotes();
        return view('moderator.dashboard',compact('pageTitle','getCounts','latestNotes','latestProfessors','latestStudents'));
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

    public function readNotification(Request $request,$id){

        auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
        return redirect($request->link);
    }
}
