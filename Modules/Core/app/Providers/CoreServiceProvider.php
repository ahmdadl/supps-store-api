<?php

namespace Modules\Core\Providers;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Mixins\BlueprintMixins;
use Modules\Core\Mixins\RouteMixins;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use BezhanSalleh\FilamentShield\FilamentShield;
use BezhanSalleh\FilamentShield\Commands;

class CoreServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = "Core";

    protected string $nameLower = "core";

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        // $this->registerViews();
        $this->loadMigrationsFrom(
            module_path($this->name, "database/migrations")
        );

        Model::shouldBeStrict(!$this->app->environment("production"));
        DB::prohibitDestructiveCommands(
            (bool) $this->app->environment("production")
        );
        Model::unguard();

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch->locales(["ar", "en"]);
        });

        // individually prohibit commands
        Commands\SetupCommand::prohibit($this->app->isProduction());
        Commands\InstallCommand::prohibit($this->app->isProduction());
        Commands\GenerateCommand::prohibit($this->app->isProduction());
        Commands\PublishCommand::prohibit($this->app->isProduction());
        // or prohibit the above commands all at once
        FilamentShield::prohibitDestructiveCommands($this->app->isProduction());
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);

        Route::mixin(new RouteMixins());
        Blueprint::mixin(new BlueprintMixins());

        if (
            $this->app->environment("local") &&
            class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)
        ) {
            $this->app->register(
                \Laravel\Telescope\TelescopeServiceProvider::class
            );
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        // $this->commands([]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path("lang/modules/" . $this->nameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->nameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(
                module_path($this->name, "lang"),
                $this->nameLower
            );
            $this->loadJsonTranslationsFrom(module_path($this->name, "lang"));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $relativeConfigPath = config("modules.paths.generator.config.path");
        $configPath = module_path($this->name, $relativeConfigPath);

        if (is_dir($configPath)) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($configPath)
            );

            /** @var \SplFileInfo $file */
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === "php") {
                    $relativePath = str_replace(
                        $configPath . DIRECTORY_SEPARATOR,
                        "",
                        $file->getPathname()
                    );
                    $configKey =
                        $this->nameLower .
                        "." .
                        str_replace(
                            [DIRECTORY_SEPARATOR, ".php"],
                            [".", ""],
                            $relativePath
                        );
                    $key =
                        $relativePath === "config.php"
                            ? $this->nameLower
                            : $configKey;

                    $this->publishes(
                        [$file->getPathname() => config_path($relativePath)],
                        "config"
                    );
                    $this->mergeConfigFrom($file->getPathname(), $key);
                }
            }
        }
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path("views/modules/" . $this->nameLower);
        $sourcePath = module_path($this->name, "resources/views");

        $this->publishes(
            [$sourcePath => $viewPath],
            ["views", $this->nameLower . "-module-views"]
        );

        $this->loadViewsFrom(
            array_merge($this->getPublishableViewPaths(), [$sourcePath]),
            $this->nameLower
        );

        // @phpstan-ignore-next-line
        $componentNamespace = $this->module_namespace(
            $this->name,
            $this->app_path(
                config("modules.paths.generator.component-class.path")
            )
        );
        Blade::componentNamespace($componentNamespace, $this->nameLower);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [];
    }

    /**
     * get publishable view paths
     *
     * @return array<int, string>
     */
    private function getPublishableViewPaths(): array
    {
        $paths = [];
        /** @var array<int, string> $configPaths */
        $configPaths = config("view.paths");
        foreach ($configPaths as $path) {
            if (is_dir($path . "/modules/" . $this->nameLower)) {
                $paths[] = $path . "/modules/" . $this->nameLower;
            }
        }

        return $paths;
    }
}
