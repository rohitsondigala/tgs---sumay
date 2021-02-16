<?php

namespace App\Observers;

use App\Models\Streams;

class StreamObserver
{
    /**
     * Handle the Streams "created" event.
     *
     * @param  \App\Models\Streams  $streams
     * @return void
     */
    public function created(Streams $streams)
    {
        //
    }

    /**
     * Handle the Streams "updated" event.
     *
     * @param  \App\Models\Streams  $streams
     * @return void
     */
    public function updated(Streams $streams)
    {
        //
    }

    /**
     * Handle the Streams "deleted" event.
     *
     * @param  \App\Models\Streams  $streams
     * @return void
     */
    public function deleted(Streams $streams)
    {

        $streams->subjects()->delete();
    }

    /**
     * Handle the Streams "restored" event.
     *
     * @param  \App\Models\Streams  $streams
     * @return void
     */
    public function restored(Streams $streams)
    {
        //
    }

    /**
     * Handle the Streams "force deleted" event.
     *
     * @param  \App\Models\Streams  $streams
     * @return void
     */
    public function forceDeleted(Streams $streams)
    {
        //
    }
}
