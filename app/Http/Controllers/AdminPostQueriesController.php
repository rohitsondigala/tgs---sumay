<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminPostQueriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    /**
     * @var string
     */
    public $route = 'admin.queries';
    /**
     * @var string
     */
    public $directory = 'admin.query-list';

    public function index(Request  $request)
    {
        $user_uuid = $request->user_uuid;
        $userDetail = user()->where('uuid',$user_uuid)->first();
        if(!$userDetail){
            abort(404);
        }
        $route = $this->route;
        $directory = $this->directory;
        $pageTitle = trans('strings.admin|queries');
        return view($directory.'.index',compact('directory','route','userDetail','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param   $uuid
     * @return Application|Factory|View|Response
     */
    public function show($uuid)
    {
        $query = post_query()->where('uuid',$uuid)->first();
        if(!$query){
            abort(404);
        }
        $pageTitle = trans('strings.admin|query|view');
        $route = $this->route;
        return view($this->directory.'.view',compact('query','pageTitle','route'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
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
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
