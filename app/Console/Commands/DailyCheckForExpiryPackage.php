<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class DailyCheckForExpiryPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:package_expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check expiry date for package and make it inactive';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $todayDate = Carbon::now()->format('d-m-y');
        $purchasePackage = purchased_packages()->where('is_purchased',1)->get();
        if(!empty($purchasePackage)){
            foreach ($purchasePackage as $packageList){
                $expiryDate = Carbon::parse($packageList->expiry_date)->format('d-m-y');
                if($todayDate == $expiryDate){
                    $purchaseUpdateArray = ['package_uuid'=>null,'purchase_date'=>null,'expiry_date'=>null,'duration_in_days'=>null,'price'=>null,'is_purchased'=>0];
                    $historyArray = [
                        'user_uuid'=>$packageList->user_uuid,
                        'package_uuid'=>$packageList->package_uuid,
                        'stream_uuid'=>$packageList->stream_uuid,
                        'subject_uuid'=>$packageList->subject_uuid,
                        'p_p_uuid'=> $packageList->uuid,
                        'purchase_date' => $packageList->purchase_date,
                        'expiry_date' => $packageList->expiry_date,
                        'duration_in_days' => $packageList->duration_in_days,
                        'price' => $packageList->price,
                        'payment_id' => !empty($packageList->payment) ? $packageList->payment->payment_id : null,
                    ];
                    purchased_packages_payment_histories()->create($historyArray);
                    purchased_packages()->where('uuid',$packageList->uuid)->update($purchaseUpdateArray);
                    purchased_packages_payments()->where('purchase_package_uuid',$packageList->uuid)->delete();
                }
            }
        }
    }
}
