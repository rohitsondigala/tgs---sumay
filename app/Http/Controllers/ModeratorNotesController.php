<?php

namespace App\Http\Controllers;

use App\Services\CrudService;
use App\Services\ModeratorService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ModeratorNotesController extends Controller
{
    /**
     * @var CrudService
     */
    public $moderatorService;
    /**
     * @var string
     */
    public $route = 'moderator.notes';
    /**
     * @var string
     */
    public $directory = 'moderator.notes';

    /**
     * ModeratorNotesController constructor.
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
    public function index(Request  $request)
    {
            $notes = notes()->with('user')->where('subject_uuid',auth()->user()->moderator->subject_uuid);
        $notesUserList = notes()->with('user')->where('subject_uuid',auth()->user()->moderator->subject_uuid)->groupBy('user_uuid')->get()->pluck('user.name','user.uuid')->prepend('All','all');

        $user_type = $request->user_type;
        $user_uuid = $request->user_uuid;
        $status = $request->status;
        $title = $request->title;

        if(!is_null($user_type) && $user_type != 'all'){
            $notes->whereHas('user.role',function ($query) use ($user_type){
                    $query->where('title',$user_type);
            });
        }
        if(!is_null($user_uuid) && $user_uuid != 'all'){
            $notes->whereHas('user',function ($query) use ($user_uuid){
                $query->where('uuid',$user_uuid);
            });
        }

        if(!is_null($status) && $status != 'all'){
            $notes->where('approve',$status);
        }

        if(!is_null($title) && $title != 'all'){
            $notes->where('title','like','%'.$title.'%');
        }

        $notes = $notes->orderBy('updated_at','DESC')->simplePaginate(10);

        $pageTitle = trans('strings.moderator|notes|index');
        $directory = $this->directory;
        $route = $this->route;
        return view($directory.'.index',compact('directory','route','notes','pageTitle','notesUserList','user_type','user_uuid','status','title'));
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
        $note = notes()->where('uuid',$uuid)->first();
        $pageTitle = trans('strings.moderator|notes|view').' : '.$note->title;
        return view($directory.'.view',compact('directory','route','note','pageTitle'));

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
        if(notes()->where('uuid',$uuid)->count() > 0){
            if(notes()->where('uuid',$uuid)->update(['approve'=>1,'approved_by'=>user_uuid()])){
                //TODO :: Push notification here for approve post
                return redirect()->back()->with(['message'=>'Post approved successfully','class'=>'alert-success']);
            }else{
                return redirect()->back()->with(['message'=>'Post not approved, please try after some time','class'=>'alert-danger']);
            }
        }else{
            return redirect()->back()->with(['message'=>'Post not found','class'=>'alert-danger']);
        }
    }
    public function rejectPost(Request $request){
        $uuid = $request->uuid;
        if(notes()->where('uuid',$uuid)->count() > 0){
            if(notes()->where('uuid',$uuid)->update(['approve'=>2,'approved_by'=>user_uuid()])){
                //TODO :: Push notification here for approve post
                return redirect()->back()->with(['message'=>'Post rejected successfully','class'=>'alert-success']);
            }else{
                return redirect()->back()->with(['message'=>'Post not rejected, please try after some time','class'=>'alert-danger']);
            }
        }else{
            return redirect()->back()->with(['message'=>'Post not found','class'=>'alert-danger']);
        }
    }
}
