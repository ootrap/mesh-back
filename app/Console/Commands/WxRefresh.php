<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Star\wechat\WeOpen;

class WxRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wx:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch wechat compnent_access_token and authorizer_access_token';

    protected $wx;

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
     * @return mixed
     */
    public function handle()
    {
        WeOpen::getComponenAccessToken();
        Log::info('俺是art命令，俺刷新了ComponenAccessToken');
    }
}
