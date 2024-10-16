<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'Kho8k\Crawler\OphimCrawler\Controllers',
], function () {
    Route::get('/plugin/khophim8k-crawler', 'CrawlController@showCrawlPage');
    Route::get('/plugin/khophim8k-crawler/options', 'CrawlerSettingController@editOptions');
    Route::put('/plugin/khophim8k-crawler/options', 'CrawlerSettingController@updateOptions');
    Route::get('/plugin/khophim8k-crawler/fetch', 'CrawlController@fetch');
    Route::post('/plugin/khophim8k-crawler/crawl', 'CrawlController@crawl');
    Route::post('/plugin/khophim8k-crawler/get-movies', 'CrawlController@getMoviesFromParams');
});
