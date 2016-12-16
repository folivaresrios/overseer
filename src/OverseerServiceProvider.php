<?php

namespace KissDev\Overseer;

use Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Class OverseerServiceProvider
 * @package KissDev\Overseer
 */
class OverseerServiceProvider extends ServiceProvider
{
    /**
     * Indicates of loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'migrations' => $this->app->databasePath() . '/migrations',
        ], 'migrations');

        $this->registerBladeDirectives();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('overseer', function ($app) {
            $auth = $app->make(\Illuminate\Contracts\Auth\Guard::class);

            return new \KissDev\Overseer\Overseer($auth);
        });
    }

    /**
     * Register the blade directives.
     *
     * @return void
     */
    public function registerBladeDirectives()
    {
        Blade::directive('isAuthorized', function ($expression) {
            return "<?php if (\\Overseer::isAuthorized({$expression})): ?>";
        });

        Blade::directive('endAuthorized', function ($expression) {
            return '<?php endif; ?>';
        });

        Blade::directive('hasProfile', function ($expression) {
            return "<?php if (\\Overseer::hasProfile({$expression})): ?>";
        });
        Blade::directive('endProfile', function ($expression) {
            return '<?php endif; ?>';
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['overseer'];
    }
}