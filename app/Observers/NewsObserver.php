<?php

namespace App\Observers;

use App\Models\News;
use App\Models\Banner;

class NewsObserver
{
    /**
     * Handle the News "deleting" event.
     * This runs before the news record is deleted.
     *
     * @param  \App\Models\News  $news
     * @return void
     */
    public function deleting(News $news): void
    {
        // Delete associated banner(s) when news is being deleted
        Banner::where('news_id', $news->id)->delete();
    }

    /**
     * Handle the News "deleted" event.
     * This runs after the news record is deleted.
     *
     * @param  \App\Models\News  $news
     * @return void
     */
    public function deleted(News $news): void
    {
        // Additional cleanup can be done here if needed
        // The banner should already be deleted in the deleting event
    }
}
