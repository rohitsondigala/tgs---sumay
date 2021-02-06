<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminCountryRequest;
use App\Services\LocationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

/**
 * Class AdminCountriesController
 * @package App\Http\Controllers
 */
class AdminCountriesController extends Controller
{
    /**
     * @var LocationService
     */
    public $locationService;
    /**
     * @var string
     */
    public $route = 'admin.country';
    /**
     * @var string
     */
    public $directory = 'admin.masters.country';

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
        $pageTitle = trans('strings.admin|country|index');
        $directory = $this->directory;
        $route = $this->route;
        $countries = country()->orderBy('id','DESC')->get();
        return view($directory.'.index',compact('directory','route','countries','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $pageTitle = trans('strings.admin|country|create');
        $country = country();
        $directory = $this->directory;
        $route = $this->route;
        return view($directory.'.form',compact('pageTitle','country','route'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminCountryRequest $request
     * @return RedirectResponse
     */
    public function store(AdminCountryRequest $request)
    {
        $route = route($this->route.'.index');
        $parameters = $request->except('_method','_token');
        if($this->locationService->storeData($parameters,country())){
            return redirect($route)->with(['class'=>'alert-success','message'=>trans('strings.admin|country|created')]);
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

        $country = country()->where('id',$id)->first();
        $pageTitle = trans('strings.admin|country|edit').' : '.$country->name;
        $directory = $this->directory;
        $route = $this->route;
        return view($directory.'.form',compact('pageTitle','route','directory','country'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminCountryRequest $request
     * @param int $id
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function update(AdminCountryRequest $request, int $id)
    {
        $route = route($this->route.'.index');
        $parameters = $request->except('_method','_token');
        if($this->locationService->storeData($parameters,country(),$id)){
            return redirect($route)->with(['class'=>'alert-success','message'=>trans('strings.admin|country|edited')]);
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
        if($this->locationService->deleteModuleItem($id,country())){
            return redirect($route)->with(['class'=>'alert-success','message'=>trans('strings.admin|delete')]);
        }else{
            return redirect()->back()->with(['class'=>'alert-danger','message'=>trans('strings.admin|fail')]);
        }
    }
}
