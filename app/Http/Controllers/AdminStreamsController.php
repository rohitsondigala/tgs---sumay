<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminCountryRequest;
use App\Http\Requests\AdminStreamRequest;
use App\Models\Streams;
use App\Services\CrudService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;

/**
 * Class AdminStreamsController
 * @package App\Http\Controllers
 */
class AdminStreamsController extends Controller
{

    /**
     * @var CrudService
     */
    public $crudService;
    /**
     * @var string
     */
    public $route = 'admin.streams';
    /**
     * @var string
     */
    public $directory = 'admin.masters.streams';

    /**
     * AdminStreamsController constructor.
     * @param CrudService $crudService
     */
    public function __construct(CrudService $crudService){
        $this->crudService = $crudService;
        generateLog('Stream table activity');
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $pageTitle = trans('strings.admin|stream|index');
        $directory = $this->directory;
        $route = $this->route;
        $streams = streams()->orderBy('id','DESC')->get();
        return view($directory.'.index',compact('directory','route','streams','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $pageTitle = trans('strings.admin|stream|create');
        $stream = streams();
        $directory = $this->directory;
        $route = $this->route;
        return view($directory.'.form',compact('pageTitle','stream','route'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStreamRequest $request
     * @return RedirectResponse
     */
    public function store(AdminStreamRequest $request)
    {
        $route = route($this->route.'.index');
        $request->merge(['slug'=>Str::slug($request->title)]);
        $parameters = $request->except('_method','_token');
        $store = $this->crudService->storeData($parameters,streams(),'stream');
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
        $stream = \streams()->where('uuid',$uuid)->first();
        $pageTitle = trans('strings.admin|country|edit').' : '.$stream->name;
        $directory = $this->directory;
        $route = $this->route;
        return view($directory.'.form',compact('pageTitle','route','directory','stream'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminStreamRequest $request
     * @param $uuid
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function update(AdminStreamRequest $request, $uuid)
    {
        $route = route($this->route.'.index');
        $request->merge(['slug'=>Str::slug($request->title)]);
        $parameters = $request->except('_method','_token');
        $store = $this->crudService->storeData($parameters,streams(),'stream',$uuid);
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
