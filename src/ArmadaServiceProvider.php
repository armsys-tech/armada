<?php

namespace Armada;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ArmadaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $published = resource_path('views/vendor/armada/components');

        // Published (customised) components win over the package copies.
        if (is_dir($published)) {
            Blade::anonymousComponentPath($published, 'armada');
        }

        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components', 'armada');

        $this->app['blade.compiler']->prepareStringsForCompilationUsing(
            fn (string $value) => TagCompiler::compile($value)
        );

        Blade::directive('armadaStyles', fn () => '<?php echo \Armada\Armada::styles(); ?>');
        Blade::directive('armadaScripts', fn () => '<?php echo \Armada\Armada::scripts(); ?>');

        Route::get('/armada/{file}', [Armada::class, 'asset'])
            ->where('file', '[a-z]+\.(css|js)')
            ->name('armada.asset');

        $this->publishes([
            __DIR__.'/../resources/views/components' => $published,
        ], 'armada-components');
    }
}
