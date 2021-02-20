<?php

namespace App\Observers;

use App\Models\Notes;

class NotesObserver
{
    /**
     * Handle the Notes "created" event.
     *
     * @param  \App\Models\Notes  $notes
     * @return void
     */
    public function created(Notes $notes)
    {
        //
    }

    /**
     * Handle the Notes "updated" event.
     *
     * @param  \App\Models\Notes  $notes
     * @return void
     */
    public function updated(Notes $notes)
    {
        //
    }

    /**
     * Handle the Notes "deleted" event.
     *
     * @param  \App\Models\Notes  $notes
     * @return void
     */
    public function deleted(Notes $notes)
    {
        $notes->image_files()->delete();
        $notes->audio_files()->delete();
        $notes->pdf_files()->delete();
    }

    /**
     * Handle the Notes "restored" event.
     *
     * @param  \App\Models\Notes  $notes
     * @return void
     */
    public function restored(Notes $notes)
    {
        //
    }

    /**
     * Handle the Notes "force deleted" event.
     *
     * @param  \App\Models\Notes  $notes
     * @return void
     */
    public function forceDeleted(Notes $notes)
    {
        //
    }
}
