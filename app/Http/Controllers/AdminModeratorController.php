<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminModeratorRequest;
use App\Http\Requests\AdminStreamRequest;
use App\Services\CrudService;
use App\Services\CustomService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;

class AdminModeratorController extends Controller
{
    /**
     * @var CustomService
     */
    public $customService;
    /**
     * @var string
     */
    public $route = 'admin.moderator';
    /**
     * @var string
     */
    public $directory = 'admin.masters.moderator';

    /**
     * AdminStreamsController constructor.
     * @param CustomService $customService
     */
    public function __construct(CustomService $customService){
        $this->customService = $customService;
        generateLog('Stream table activity');
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $pageTitle = trans('strings.admin|moderator|index');
        $directory = $this->directory;
        $route = $this->route;
        $users = user()->ofRole('MODERATOR')->orderBy('id','DESC')->get();
        return view($directory.'.index',compact('directory','route','users','pageTitle'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $pageTitle = trans('strings.admin|moderator|create');
        $user = user();
        $streams = streams()->pluck('title','uuid')->prepend('Select A Stream','');
        $countries = country()->orderBy('name','ASC')->pluck('name','id')->prepend('Select Country','');
        $directory = $this->directory;
        $route = $this->route;
        return view($directory.'.form',compact('pageTitle','user','route','streams','countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminModeratorRequest $request
     * @return RedirectResponse
     */
    public function store(AdminModeratorRequest $request)
    {

        $store = $this->customService->storeModerator($request);
        $route = route($this->route.'.index');
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
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $uuid
     * @return Application|Factory|View
     */
    public function edit($uuid)
    {
        $user = \user()->where('uuid',$uuid)->first();
        $subjects = subjects()->where('stream_uuid',$user->moderator->stream_uuid)->pluck('title','uuid')->prepend('Select Subject','');
        $pageTitle = trans('strings.admin|moderator|edit').' : '.$user->name;
        $streams = streams()->pluck('title','uuid')->prepend('Select A Stream','');

        $countries = country()->orderBy('name','ASC')->pluck('name','id')->prepend('Select Country','');
        $states = state()->where('country_id',$user->country)->orderBy('name','ASC')->pluck('name','id')->prepend('Select State','');
        $cities = city()->where('state_id',$user->state)->orderBy('name','ASC')->pluck('name','id')->prepend('Select City','');
        $directory = $this->directory;
        $route = $this->route;
        return view($directory.'.form',compact('pageTitle','route','directory','user','streams','countries','subjects','states','cities'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminModeratorRequest  $request
     * @param $uuid
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function update(AdminModeratorRequest $request, $uuid)
    {

        $route = route($this->route.'.index');
        $store = $this->customService->storeModerator($request,$uuid);
        if($store['success']){
            return redirect($route)->with(['message'=>$store['message'],'class'=>'alert-success']);
        }else{
            return redirect()->back()->with(['message'=>$store['message'],'class'=>'alert-danger']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $uuid
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function destroy($uuid)
    {
        $route = route($this->route.'.index');
        $delete = $this->customService->deleteModerator($uuid);
        if($delete['success']){
            return redirect($route)->with(['message'=>$delete['message'],'class'=>'alert-success']);
        }else{
            return redirect()->back()->with(['message'=>$delete['message'],'class'=>'alert-danger']);
        }
    }
}
