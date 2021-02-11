<?php

namespace App\Observers;

use App\Models\Packages;

class PackagesObserver
{
    /**
     * Handle the Packages "created" event.
     *
     * @param  \App\Models\Packages  $packages
     * @return void
     */
    public function created(Packages $packages)
    {
        //
    }

    /**
     * Handle the Packages "updated" event.
     *
     * @param  \App\Models\Packages  $packages
     * @return void
     */
    public function updated(Packages $packages)
    {
        //
    }

    /**
     * Handle the Packages "deleted" event.
     *
     * @param  \App\Models\Packages  $packages
     * @return void
     */
    public function deleted(Packages $packages)
    {
        //
    }

    /**
     * Handle the Packages "restored" event.
     *
     * @param  \App\Models\Packages  $packages
     * @return void
     */
    public function restored(Packages $packages)
    {
        //
    }

    /**
     * Handle the Packages "force deleted" event.
     *
     * @param  \App\Models\Packages  $packages
     * @return void
     */
    public function forceDeleted(Packages $packages)
    {
        //
    }
}
