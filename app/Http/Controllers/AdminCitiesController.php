<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdmincityRequest;
use App\Services\LocationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

class AdminCitiesController extends Controller
{
    /**
     * @var LocationService
     */
    public $locationService;
    /**
     * @var string
     */
    public $route = 'admin.city';
    /**
     * @var string
     */
    public $directory = 'admin.masters.city';

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
        $countries = country()->orderBy('name','ASC')->pluck('name','id');

        $pageTitle = trans('strings.admin|city|index');
        $directory = $this->directory;
        $route = $this->route;
        $cities = city()
//            ->whereHas('state',function ($query){
//            $query->whereHas('country',function ($query){
//                $query->where('name','India');
//            });
//            $query->orderBy('name','ASC');
//        })
        ->get();
        return view($directory.'.index',compact('directory','route','cities','pageTitle','countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $pageTitle = trans('strings.admin|city|create');
        $countries = country()->orderBy('name','ASC')->pluck('name','id');
        $states = state()->orderBy('name','ASC')->pluck('name','id');
        $city = city();
        $directory = $this->directory;
        $route = $this->route;
        return view($directory.'.form',compact('pageTitle','city','route','states','countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminCityRequest $request
     * @return RedirectResponse
     */
    public function store(AdminCityRequest $request)
    {
        $route = route($this->route.'.index');
        $parameters = $request->except('_method','_token');
        if($this->locationService->storeData($parameters,city())){
            return redirect($route)->with(['class'=>'alert-success','message'=>trans('strings.admin|city|created')]);
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

        $city = city()->where('id',$id)->first();
        $countries = country()->orderBy('name','ASC')->pluck('name','id');
        $states = state()->orderBy('name','ASC')->pluck('name','id');
        $pageTitle = trans('strings.admin|city|edit').' : '.$city->name;
        $directory = $this->directory;
        $route = $this->route;
        return view($directory.'.form',compact('pageTitle','route','directory','city','countries','states'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminCityRequest $request
     * @param int $id
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function update(AdminCityRequest $request, int $id)
    {
        $route = route($this->route.'.index');
        $parameters = $request->except('_method','_token');
        if($this->locationService->storeData($parameters,city(),$id)){
            return redirect($route)->with(['class'=>'alert-success','message'=>trans('strings.admin|city|edited')]);
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
        if($this->locationService->deleteModuleItem($id,city())){
            return redirect($route)->with(['class'=>'alert-success','message'=>trans('strings.admin|delete')]);
        }else{
            return redirect()->back()->with(['class'=>'alert-danger','message'=>trans('strings.admin|fail')]);
        }
    }


}
