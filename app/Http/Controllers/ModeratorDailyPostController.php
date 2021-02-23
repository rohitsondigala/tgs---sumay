<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModeratorDailPostRequest;
use App\Services\CrudService;
use App\Services\ModeratorService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;

class ModeratorDailyPostController extends Controller
{
    /**
     * @var CrudService
     */
    public $moderatorService;
    /**
     * @var string
     */
    public $route = 'moderator.daily-posts';
    /**
     * @var string
     */
    public $directory = 'moderator.daily-posts';

    /**
     * ModeratorDailyPostController constructor.
     * @param ModeratorService $moderatorService
     */
    public function __construct(ModeratorService $moderatorService){
        $this->moderatorService = $moderatorService;
//        generateLog('Moderator table activity');
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $pageTitle = trans('strings.moderator|daily_post|index');
        $directory = $this->directory;
        $route = $this->route;
        $dailyPosts = moderator_daily_posts()->where('user_uuid',user_uuid())->orderBy('id','DESC')->get();
        return view($directory.'.index',compact('directory','route','dailyPosts','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $pageTitle = trans('strings.moderator|daily_post|create');
        $dailyPost = moderator_daily_posts();
        $directory = $this->directory;
        $route = $this->route;
        return view($directory.'.form',compact('pageTitle','dailyPost','route'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ModeratorDailPostRequest $request
     * @return RedirectResponse
     */
    public function store(ModeratorDailPostRequest $request)
    {
        $route = route($this->route.'.index');
        $request->merge(['slug'=>Str::slug($request->title)]);
        $parameters = $request->except('_method','_token','image');
//        $filePath = uploadMedia($request->image,'daily-posts');
//        $parameters['image'] = $filePath;
        $parameters['user_uuid'] =auth()->user()->uuid;
        $parameters['moderator_subject_uuid'] =auth()->user()->moderator->uuid;
        $store = $this->moderatorService->storeData($parameters,moderator_daily_posts(),'daily_post');
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
        $dailyPost = \moderator_daily_posts()->where('uuid',$uuid)->first();
        $pageTitle = trans('strings.moderator|daily_post|edit').' : '.$dailyPost->name;
        $directory = $this->directory;
        $route = $this->route;
        return view($directory.'.form',compact('pageTitle','route','directory','dailyPost'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param ModeratorDailPostRequest $request
     * @param $uuid
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function update(ModeratorDailPostRequest $request, $uuid)
    {

        $route = route($this->route.'.index');
        $request->merge(['slug'=>Str::slug($request->title)]);
        $parameters = $request->except('_method','_token','image');
        if(!empty($request->image)){
            $filePath = uploadMedia($request->image,'daily-posts');
            $parameters['image'] = $filePath;
        }
        $store = $this->moderatorService->storeData($parameters,moderator_daily_posts(),'daily_post',$uuid);
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
        $delete = $this->moderatorService->deleteModuleItem($uuid,\moderator_daily_posts());
        if($delete['success']){
            return redirect($route)->with(['message'=>$delete['message'],'class'=>'alert-success']);
        }else{
            return redirect()->back()->with(['message'=>$delete['message'],'class'=>'alert-danger']);
        }
    }
}
