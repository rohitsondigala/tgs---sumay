<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminNotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @var string
     */
    public $route = 'admin.notes';
    /**
     * @var string
     */
    public $directory = 'admin.notes-list';

    public function index(Request  $request)
    {
        $user_uuid = $request->user_uuid;
        $userDetail = user()->where('uuid',$user_uuid)->first();
        if(!$userDetail){
            abort(404);
        }
        $route = $this->route;
        $directory = $this->directory;
        $pageTitle = trans('strings.admin|notes');
        return view($directory.'.index',compact('directory','route','userDetail','pageTitle'));
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
     */
    public function show($uuid)
    {
        $note = notes()->where('uuid',$uuid)->first();
        if(!$note){
            abort(404);
        }
        $pageTitle = trans('strings.admin|notes|view');
        $route = $this->route;
        return view($this->directory.'.view',compact('note','pageTitle','route'));
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
