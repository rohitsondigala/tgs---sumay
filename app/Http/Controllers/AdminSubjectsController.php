<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminStateRequest;
use App\Http\Requests\AdminSubjectRequest;
use App\Services\CrudService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;

class AdminSubjectsController extends Controller
{
    /**
     * @var CrudService
     */
    public $crudService;
    /**
     * @var string
     */
    public $route = 'admin.subjects';
    /**
     * @var string
     */
    public $directory = 'admin.masters.subjects';

    /**
     * AdminCountriesController constructor.
     * @param CrudService $crudService
     */
    public function __construct(CrudService $crudService){
        $this->crudService = $crudService;
        generateLog('Subject table activity');

    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $pageTitle = trans('strings.admin|subject|index');
        $directory = $this->directory;
        $route = $this->route;
        $subjects = subjects()->orderBy('id','DESC')->get();
        return view($directory.'.index',compact('directory','route','subjects','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $pageTitle = trans('strings.admin|subject|create');
        $streams =streams()->pluck('title','uuid');
        $subject = subjects();
        $directory = $this->directory;
        $route = $this->route;
        return view($directory.'.form',compact('pageTitle','subject','route','streams'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStateRequest $request
     * @return RedirectResponse
     */
    public function store(AdminSubjectRequest $request)
    {
        $route = route($this->route.'.index');
        $request->merge(['slug'=>Str::slug($request->title)]);
        $parameters = $request->except('_method','_token');
        $store = $this->crudService->storeData($parameters,subjects(),'subject');
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
     * @param  $uuid
     */
    public function edit($uuid)
    {

        $subject = subjects()->where('uuid',$uuid)->first();
        $streams = streams()->pluck('title','uuid');
        $pageTitle = trans('strings.admin|subject|edit').' : '.$subject->title;
        $directory = $this->directory;
        $route = $this->route;
        return view($directory.'.form',compact('pageTitle','route','directory','subject','streams'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminSubjectRequest $request
     * @param $uuid
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function update(AdminSubjectRequest $request, $uuid)
    {
        $route = route($this->route.'.index');
        $request->merge(['slug'=>Str::slug($request->title)]);
        $parameters = $request->except('_method','_token');
        $store = $this->crudService->storeData($parameters,subjects(),'subject',$uuid);
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
        $delete = $this->crudService->deleteModuleItem($uuid,\subjects());
        if($delete['success']){
            return redirect($route)->with(['message'=>$delete['message'],'class'=>'alert-success']);
        }else{
            return redirect()->back()->with(['message'=>$delete['message'],'class'=>'alert-danger']);
        }
    }
}
