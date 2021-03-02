<?php

namespace App\Http\Controllers;

use App\Services\CrudService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminProfessorController extends Controller
{
    /**
     * @var CrudService
     */
    public $crudService;
    /**
     * @var string
     */
    public $route = 'admin.professor';
    /**
     * @var string
     */
    public $directory = 'admin.professor-list';
    /**
     * AdminStudentController constructor.
     * @param CrudService $crudService
     */
    public function __construct(CrudService $crudService){
        $this->crudService = $crudService;
    }
    public function index()
    {
        $pageTitle = trans('strings.admin|professor|index');
        $directory = $this->directory;
        $route = $this->route;
        $professors = user()->ofRole('PROFESSOR')->ofVerify()->orderBy('id','DESC')->get();
        return view($directory.'.index',compact('directory','route','professors','pageTitle'));
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
     * @param int $uuid
     * @param Request $request
     * @return Application|Factory|View|\Illuminate\Http\Response
     */
    public function show($uuid,Request $request)
    {
        $directory = $this->directory;
        $route = $this->route;
        $detail = user()->where('uuid',$uuid)->ofRole('PROFESSOR')->ofVerify()->orderBy('id','DESC')->first();
        if(!$detail){
            abort(404);
        }
        if($request->type == 'ACTIVITIES'){
            $notes = notes()->ofApprove()->where('user_uuid',$uuid)->orderBy('updated_at','DESC')->take(10)->get();
            $queries = post_query()->where('to_user_uuid',$uuid)->orderBy('updated_at','DESC')->take(10)->get();
            $pageTitle = trans('strings.admin|professor|activities');
            return view($directory.'.activities',compact('directory','route','detail','pageTitle','notes','queries'));
        }else{
            $pageTitle = trans('strings.admin|professor|view');
            return view($directory.'.view',compact('directory','route','detail','pageTitle'));
        }

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


}
