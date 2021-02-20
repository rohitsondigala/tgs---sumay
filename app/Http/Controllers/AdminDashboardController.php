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
        return view('admin.dashboard',compact('getCounts'));
    }
}
