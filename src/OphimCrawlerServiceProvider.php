<?php

namespace Kho8k\Crawler\OphimCrawler;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as SP;
use Kho8k\Crawler\OphimCrawler\Console\CrawlerScheduleCommand;
use Kho8k\Crawler\OphimCrawler\Option;

class OphimCrawlerServiceProvider extends SP
{
    /**
     * Get the policies defined on the provider.
     *
     * @return array
     */
    public function policies()
    {
        return [];
    }

    public function register()
    {

        config(['plugins' => array_merge(config('plugins', []), [
            'khophim8k/khophim8k-crawler' =>
            [
                'name' => 'phim8k Crawler',
                'package_name' => 'khophim8k/khophim8k-crawler',
                'icon' => 'la la-hand-grab-o',
                'entries' => [
                    ['name' => 'Xvnapi Crawler', 'icon' => 'la la-hand-grab-o', 'url' => backpack_url('/plugin/khophim8k-crawler')],
                    ['name' => 'Option', 'icon' => 'la la-cog', 'url' => backpack_url('/plugin/khophim8k-crawler/options')],
                ],
            ]
        ])]);

        config(['logging.channels' => array_merge(config('logging.channels', []), [
            'khophim8k-crawler' => [
                'driver' => 'daily',
                'path' => storage_path('logs/khophim8k/khophim8k-crawler.log'),
                'level' => env('LOG_LEVEL', 'debug'),
                'days' => 7,
            ],
        ])]);

        config(['ophim.updaters' => array_merge(config('ophim.updaters', []), [
            [
                'name' => 'Kho8k Crawler',
                'handler' => 'Kho8k\Crawler\OphimCrawler\Crawler'
            ]
        ])]);
    }

    public function boot()
    {
        $this->commands([
            CrawlerScheduleCommand::class,
        ]);

        $this->app->booted(function () {
            $this->loadScheduler();
        });

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'khophim8k-crawler');
    }

    protected function loadScheduler()
    {
        $schedule = $this->app->make(Schedule::class);
        $schedule->command('ophim:plugins:khophim8k-crawler:schedule')->cron(Option::get('crawler_schedule_cron_config', '*/10 * * * *'))->withoutOverlapping();
    }
}
