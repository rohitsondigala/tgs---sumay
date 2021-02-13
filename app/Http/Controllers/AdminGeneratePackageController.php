<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminGeneratePackageRequest;
use App\Services\CrudService;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminGeneratePackageController extends Controller
{
    /**
     * @var CrudService
     */
    public $crudService;
    /**
     * @var string
     */
    public $route = 'admin.generate-package';
    /**
     * @var string
     */
    public $directory = 'admin.masters.generate-package';

    /**
     * AdminStreamsController constructor.
     * @param CrudService $crudService
     */
    public function __construct(CrudService $crudService)
    {
        $this->crudService = $crudService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $pageTitle = trans('strings.admin|generate-package|index');
        $directory = $this->directory;
        $route = $this->route;
        $students = user()->ofRole('STUDENT')->ofVerify()->orderBy('name', 'ASC')->pluck('name', 'uuid')->prepend('Select Student', '');
        $packages = packages()->orderBy('id', 'DESC')->pluck('title', 'uuid')->prepend('Select Package', '');
        $purchasedPackage = purchased_packages();
        return view($directory . '.form', compact('directory', 'route', 'packages', 'pageTitle', 'students', 'purchasedPackage'));
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AdminGeneratePackageRequest $request)
    {

        $user_uuid = $request->user_uuid;
        if (user()->where('uuid', $user_uuid)->count() <= 0) {
            return redirect()->back()->with(['message' => 'User not found!!', 'class' => 'alert-danger']);
        }

        $package_uuid = $request->package_uuid;
        if (packages()->where('uuid', $package_uuid)->count() <= 0) {
            return redirect()->back()->with(['message' => 'Package not found!!', 'class' => 'alert-danger']);
        }
        if(purchased_packages()->where('package_uuid',$package_uuid)->where('user_uuid',$user_uuid)->count() > 0){
            return redirect()->back()->with(['message' => 'Package is already purchased', 'class' => 'alert-danger']);
        }

        $packageDetail = packages()->where('uuid', $package_uuid)->first();

        $stream_uuid = $packageDetail->stream->uuid;
        $subject_uuid = $packageDetail->subject->uuid;
        $purchase_date = Carbon::now();
        $expiry_date = getExpiryDateByMonth($request->duration_month);
        $duration_in_days = getDurationInDaysByExpiryDate($expiry_date);
        $purchaseArray = [
            'user_uuid' => $user_uuid,
            'package_uuid' => $package_uuid,
            'stream_uuid' => $stream_uuid,
            'subject_uuid' => $subject_uuid,
            'purchase_date' => $purchase_date,
            'expiry_date' => $expiry_date,
            'duration_in_days' => $duration_in_days,
        ];
        $route = route($this->route.'.index');

        if (purchased_packages()->create($purchaseArray)) {
            return redirect($route)->with(['message' => "Package generated successfully", 'class' => 'alert-success']);
        } else {
            return redirect()->back()->with(['message' => "Fail to generate package", 'class' => 'alert-danger']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
