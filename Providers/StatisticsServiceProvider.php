<?php

namespace Modules\Statistics\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Traits\CanGetSidebarClassForModule;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Statistics\Events\Handlers\RegisterStatisticsSidebar;
use Spatie\Analytics\AnalyticsServiceProvider;

class StatisticsServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration, CanGetSidebarClassForModule;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
        $this->registerAlias();

        $this->app->register(AnalyticsServiceProvider::class);

        $this->app['events']->listen(
            BuildingSidebar::class,
            $this->getSidebarClassForModule('statistics', RegisterStatisticsSidebar::class)
        );
    }

    public function boot()
    {
        $this->publishConfig('statistics', 'permissions');
        $this->publishConfig('statistics', 'settings');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    public function registerAlias()
    {
        $aliasLoader = AliasLoader::getInstance();
        $aliasLoader->alias('Analytics', \Spatie\Analytics\AnalyticsFacade::class);
    }

    public function registerBindings()
    {

    }
}
