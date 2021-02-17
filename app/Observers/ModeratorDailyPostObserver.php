<?php

namespace App\Observers;

use App\Models\ModeratorDailyPost;

class ModeratorDailyPostObserver
{
    /**
     * Handle the ModeratorDailyPost "created" event.
     *
     * @param  \App\Models\ModeratorDailyPost  $moderatorDailyPost
     * @return void
     */
    public function created(ModeratorDailyPost $moderatorDailyPost)
    {
        //
    }

    /**
     * Handle the ModeratorDailyPost "updated" event.
     *
     * @param  \App\Models\ModeratorDailyPost  $moderatorDailyPost
     * @return void
     */
    public function updated(ModeratorDailyPost $moderatorDailyPost)
    {
        //
    }

    /**
     * Handle the ModeratorDailyPost "deleted" event.
     *
     * @param  \App\Models\ModeratorDailyPost  $moderatorDailyPost
     * @return void
     */
    public function deleted(ModeratorDailyPost $moderatorDailyPost)
    {
        //
    }

    /**
     * Handle the ModeratorDailyPost "restored" event.
     *
     * @param  \App\Models\ModeratorDailyPost  $moderatorDailyPost
     * @return void
     */
    public function restored(ModeratorDailyPost $moderatorDailyPost)
    {
        //
    }

    /**
     * Handle the ModeratorDailyPost "force deleted" event.
     *
     * @param  \App\Models\ModeratorDailyPost  $moderatorDailyPost
     * @return void
     */
    public function forceDeleted(ModeratorDailyPost $moderatorDailyPost)
    {
        //
    }
}
