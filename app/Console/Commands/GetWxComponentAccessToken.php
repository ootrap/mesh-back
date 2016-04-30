<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Star\wechat\WeOpen;

class GetWxComponentAccessToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wx:getComponenAccessToken';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch wechat compnent access token';

    protected $wx;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(WeOpen $wx)
    {
        parent::__construct();
        $this->wx = $wx;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->wx->getComponenAccessToken();
    }
}
