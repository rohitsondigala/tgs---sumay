<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminStateRequest;
use App\Services\LocationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

class AdminStatesController extends Controller
{
    /**
     * @var LocationService
     */
    public $locationService;
    /**
     * @var string
     */
    public $route = 'admin.state';
    /**
     * @var string
     */
    public $directory = 'admin.masters.state';

    /**
     * AdminCountriesController constructor.
     * @param LocationService $locationService
     */
    public function __construct(LocationService $locationService){
        $this->locationService = $locationService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $pageTitle = trans('strings.admin|state|index');
        $directory = $this->directory;
        $route = $this->route;
        $states = state()
//            ->whereHas('country',function ($query){
//                $query->where('name','India');
//            })
            ->orderBy('id','DESC')->get();
        return view($directory.'.index',compact('directory','route','states','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $pageTitle = trans('strings.admin|state|create');
        $countries = country()->orderBy('name','ASC')->pluck('name','id');
        $state = state();
        $directory = $this->directory;
        $route = $this->route;
        return view($directory.'.form',compact('pageTitle','state','route','countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStateRequest $request
     * @return RedirectResponse
     */
    public function store(AdminStateRequest $request)
    {
        $route = route($this->route.'.index');
        $parameters = $request->except('_method','_token');
        if($this->locationService->storeData($parameters,state())){
            return redirect($route)->with(['class'=>'alert-success','message'=>trans('strings.admin|state|created')]);
        }else{
            return redirect()->back()->with(['class'=>'alert-danger','message'=>trans('strings.admin|fail')]);
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
     * @param  $id
     */
    public function edit($id)
    {

        $state = state()->where('id',$id)->first();
        $countries = country()->orderBy('name','ASC')->pluck('name','id');
        $pageTitle = trans('strings.admin|state|edit').' : '.$state->name;
        $directory = $this->directory;
        $route = $this->route;
        return view($directory.'.form',compact('pageTitle','route','directory','state','countries'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminstateRequest $request
     * @param int $id
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function update(AdminstateRequest $request, int $id)
    {
        $route = route($this->route.'.index');
        $parameters = $request->except('_method','_token');
        if($this->locationService->storeData($parameters,state(),$id)){
            return redirect($route)->with(['class'=>'alert-success','message'=>trans('strings.admin|state|edited')]);
        }else{
            return redirect()->back()->with(['class'=>'alert-danger','message'=>trans('strings.admin|fail')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function destroy(int $id)
    {
        $route = route($this->route.'.index');
        if($this->locationService->deleteModuleItem($id,state())){
            return redirect($route)->with(['class'=>'alert-success','message'=>trans('strings.admin|delete')]);
        }else{
            return redirect()->back()->with(['class'=>'alert-danger','message'=>trans('strings.admin|fail')]);
        }
    }
}
