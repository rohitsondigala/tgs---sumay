<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminPushNotificationRequest;
use App\Notifications\SendGeneralNotification;
use App\Services\CrudService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class AdminNotificationController extends Controller
{
    /**
     * @var CrudService
     */
    public $crudService;
    /**
     * @var string
     */
    public $route = 'admin.notifications';
    /**
     * @var string
     */
    public $directory = 'admin.masters.notifications';

    /**
     * AdminStreamsController constructor.
     * @param CrudService $crudService
     */
    public function __construct(CrudService $crudService){
        $this->crudService = $crudService;
//        generateLog('Stream table activity');
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {


        $pageTitle = trans('strings.admin|notification|index');
        $directory = $this->directory;
        $route = $this->route;
        return view($directory.'.index',compact('directory','route','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {

        $pageTitle = trans('strings.admin|notification|create');
        $notification = push_notifications();
        $directory = $this->directory;
        $route = $this->route;
        return view($directory.'.form',compact('pageTitle','notification','route'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminPushNotificationRequest $request
     * @return RedirectResponse
     */
    public function store(AdminPushNotificationRequest $request)
    {
        $route = route($this->route.'.index');
        $parameters = $request->except('_method','_token','image','role');
        $filePath = uploadMedia($request->image,'packages');
        $parameters['image'] = $filePath;
        $parameters['type'] = 'general';

        $store = $this->crudService->storeData($parameters,push_notifications(),'notification');
        $model  = $store['model'];
        if($model->student && $model->professor){
            $user = user()->ofNotRole('MODERATOR')->ofNotRole('ADMIN')->ofVerify()->get();
        }elseif($model->professor){
            $user = user()->ofRole('PROFESSOR')->ofVerify()->get();
        }elsE{
            $user = user()->ofRole('STUDENT')->ofVerify()->get();
        }
        Notification::send($user, new SendGeneralNotification($store['model']));
        if($store['success']){
            return redirect($route)->with(['message'=>$store['message'],'class'=>'alert-success']);
        }else{
            return redirect()->back()->with(['message'=>$store['message'],'class'=>'alert-danger']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
