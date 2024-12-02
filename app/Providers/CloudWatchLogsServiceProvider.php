<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Illuminate\Support\Facades\Config;

class CloudWatchLogsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CloudWatchLogsClient::class, function ($app) {
            return new CloudWatchLogsClient([
                'credentials' => [
                    'key' => Config::get('services.aws.key'),
                    'secret' => Config::get('services.aws.secret'),
                ],
                'region' => Config::get('services.aws.region'),
                'version' => 'latest',
            ]);
        });
    }
}
