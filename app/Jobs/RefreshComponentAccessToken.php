<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Star\wechat\WeOpen;

class RefreshComponentAccessToken extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $wechat = new WeOpen;
        $wechat->getComponenAccessToken();
    }
}
