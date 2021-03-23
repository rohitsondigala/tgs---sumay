<?php

namespace App\Http\Controllers;

use App\Services\CrudService;
use App\Services\ModeratorService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ModeratorQueriesController extends Controller
{
    /**
     * @var CrudService
     */
    public $moderatorService;
    /**
     * @var string
     */
    public $route = 'moderator.queries';
    /**
     * @var string
     */
    public $directory = 'moderator.queries';

    /**
     * ModeratorQueriesController constructor.
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
    public function index(Request $request)
    {
        $queries = post_query()->with('from_user','to_user')->where('subject_uuid',auth()->user()->moderator->subject_uuid);
        $from_user_query_list = post_query()->with('from_user')->where('subject_uuid',auth()->user()->moderator->subject_uuid)->groupBy('from_user_uuid')->get()->pluck('from_user.name','from_user.uuid')->prepend('All','all');
        $to_user_query_list = post_query()->with('to_user')->where('subject_uuid',auth()->user()->moderator->subject_uuid)->groupBy('to_user_uuid')->get()->pluck('to_user.name','to_user.uuid')->prepend('All','all');

        $user_type = $request->user_type;
        $from_user_uuid = $request->from_user_uuid;
        $to_user_uuid = $request->to_user_uuid;
        $status = $request->status;
        $title = $request->title;

//        if(!is_null($user_type) && $user_type != 'all'){
//            $queries->whereHas('user.role',function ($query) use ($user_type){
//                $query->where('title',$user_type);
//            });
//        }

//        if(!is_null($user_uuid) && $user_uuid != 'all'){
//            $queries->whereHas('user',function ($query) use ($user_uuid){
//                $query->where('uuid',$user_uuid);
//            });
//        }

        if(!is_null($status) && $status != 'all'){
            $queries->where('approve',$status);
        }

        if(!is_null($title) && $title != 'all'){
            $queries->where('title','like','%'.$title.'%');
        }

        $queries = $queries->orderBy('updated_at','DESC')->simplePaginate(10);

        $pageTitle = trans('strings.moderator|queries|index');
        $directory = $this->directory;
        $route = $this->route;
        return view($directory.'.index',compact('directory','route','queries','pageTitle','from_user_query_list','to_user_query_list','user_type','status','title','from_user_uuid','to_user_uuid'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param $uuid
     * @return Application|Factory|View|Response
     */
    public function show($uuid)
    {
        $directory = $this->directory;
        $route = $this->route;
        $query = post_query()->where('uuid',$uuid)->first();
        $pageTitle = trans('strings.moderator|queries|view').' : '.$query->title;
        return view($directory.'.view',compact('directory','route','query','pageTitle'));

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

    public function approvePost(Request $request){
        $uuid = $request->uuid;
        if(post_query()->where('uuid',$uuid)->count() > 0){
            if(post_query()->where('uuid',$uuid)->update(['approve'=>1,'approved_by'=>user_uuid()])){
                $noteDetail = post_query()->where('uuid',$uuid)->first();
                sendPushNotification($noteDetail->from_user_uuid,'post','notification|query|title','notification|query|approved');
                sendPushNotification($noteDetail->to_user_uuid,'post','notification|query|received','notification|query|received|description');
                return redirect()->back()->with(['message'=>'Query approved successfully','class'=>'alert-success']);
            }else{
                return redirect()->back()->with(['message'=>'Query not approved, please try after some time','class'=>'alert-danger']);
            }
        }else{
            return redirect()->back()->with(['message'=>'Query not found','class'=>'alert-danger']);
        }
    }
    public function rejectPost(Request $request){
        $uuid = $request->uuid;
        if(post_query()->where('uuid',$uuid)->count() > 0){
            if(post_query()->where('uuid',$uuid)->update(['approve'=>2,'approved_by'=>user_uuid()])){
                $noteDetail = post_query()->where('uuid',$uuid)->first();
                sendPushNotification($noteDetail->from_user_uuid,'post','notification|query|title','notification|query|rejected');
                return redirect()->back()->with(['message'=>'Query rejected successfully','class'=>'alert-success']);
            }else{
                return redirect()->back()->with(['message'=>'Query not rejected, please try after some time','class'=>'alert-danger']);
            }
        }else{
            return redirect()->back()->with(['message'=>'Query not found','class'=>'alert-danger']);
        }
    }
}
