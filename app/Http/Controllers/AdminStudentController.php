<?php

namespace App\Http\Controllers;

use App\Services\CrudService;
use Illuminate\Http\Request;

class AdminStudentController extends Controller
{
    /**
     * @var CrudService
     */
    public $crudService;
    /**
     * @var string
     */
    public $route = 'admin.student';
    /**
     * @var string
     */
    public $directory = 'admin.student-list';
    /**
     * AdminStudentController constructor.
     * @param CrudService $crudService
     */
    public function __construct(CrudService $crudService){
        $this->crudService = $crudService;
    }
    public function index()
    {

        $pageTitle = trans('strings.admin|student|index');
        $directory = $this->directory;
        $route = $this->route;
        $students = user()->with('student_subjects')->ofRole('STUDENT')->ofVerify()->orderBy('id','DESC')->get();
        return view($directory.'.index',compact('directory','route','students','pageTitle'));

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
