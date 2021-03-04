<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public $adminService;

    public function __construct(AdminService $adminService){
        $this->adminService = $adminService;
    }
    public function dashboard(){
        $getCounts = $this->adminService->getDashboardCounts();
        $latestStudents = $this->adminService->getLatestUsers('STUDENT');
        $latestProfessors = $this->adminService->getLatestUsers('PROFESSOR');
        $latestNotes = $this->adminService->getLatestNotes();
        return view('admin.dashboard',compact('getCounts','latestStudents','latestProfessors','latestNotes'));
    }
}
