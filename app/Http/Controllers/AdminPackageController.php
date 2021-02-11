<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminPackageRequest;
use App\Services\CrudService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;

class AdminPackageController extends Controller
{

    /**
     * @var CrudService
     */
    public $crudService;
    /**
     * @var string
     */
    public $route = 'admin.packages';
    /**
     * @var string
     */
    public $directory = 'admin.masters.packages';

    /**
     * AdminStreamsController constructor.
     * @param CrudService $crudService
     */
    public function __construct(CrudService $crudService){
        $this->crudService = $crudService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $pageTitle = trans('strings.admin|package|index');
        $directory = $this->directory;
        $route = $this->route;
        $packages = packages()->with('subjects')->orderBy('id','DESC')->get();
        return view($directory.'.index',compact('directory','route','packages','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $pageTitle = trans('strings.admin|package|create');
        $package = packages();
        $directory = $this->directory;
        $route = $this->route;
        $streams =streams()->pluck('title','uuid')->prepend('Select Stream','');
        //TODO::Active
        return view($directory.'.form',compact('pageTitle','package','route','streams'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminPackageRequest $request
     * @return RedirectResponse
     */
    public function store(AdminPackageRequest $request)
    {
        $route = route($this->route.'.index');
        $request->merge(['slug'=>Str::slug($request->title)]);
        $request->merge(['user_uuid'=>user_uuid()]);
        $parameters = $request->except('_method','_token','stream_uuid','subjects','image');
        $filePath = uploadMedia($request->image,'packages');
        $parameters['image'] = $filePath;
        $store = $this->crudService->storeData($parameters,packages(),'package');
        if($store['success']){
            $packageDetail= $store['model'];
            foreach ($request->subjects as $uuid){
                package_subjects()->create(['package_uuid'=>$packageDetail->uuid,'stream_uuid'=>$request->stream_uuid,'subject_uuid'=>$uuid]);
            }
            return redirect($route)->with(['message'=>$store['message'],'class'=>'alert-success']);
        }else{
            return redirect()->back()->with(['message'=>$store['message'],'class'=>'alert-danger']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View|Response
     */
    public function show($uuid)
    {
        $package = \packages()->where('uuid',$uuid)->first();
        $pageTitle = trans('strings.admin|package|view').' : '.$package->title;
        $directory = $this->directory;
        $route = $this->route;
        return view($directory.'.view',compact('pageTitle','route','directory','package'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $uuid
     * @return Application|Factory|View
     */
    public function edit($uuid)
    {
        $package = \packages()->where('uuid',$uuid)->first();
        $pageTitle = trans('strings.admin|package|edit').' : '.$package->title;
        $directory = $this->directory;
        $route = $this->route;
        return view($directory.'.form',compact('pageTitle','route','directory','package'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminPackageRequest $request
     * @param $uuid
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function update(AdminPackageRequest $request, $uuid)
    {
        $route = route($this->route.'.index');
        $request->merge(['slug'=>Str::slug($request->title)]);
        $parameters = $request->except('_method','_token','image');
        if(!empty($request->image)){
            $filePath = uploadMedia($request->image,'daily-posts');
            $parameters['image'] = $filePath;
        }
        $store = $this->crudService->storeData($parameters,packages(),'package',$uuid);
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
        $delete = $this->crudService->deleteModuleItem($uuid,\streams());
        if($delete['success']){
            return redirect($route)->with(['message'=>$delete['message'],'class'=>'alert-success']);
        }else{
            return redirect()->back()->with(['message'=>$delete['message'],'class'=>'alert-danger']);
        }
    }
}
