<?php

namespace Collective\LaravelTypescriptPreset;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Console\PresetCommand;

class CollectiveServiceProvider extends ServiceProvider
{
    
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        PresetCommand::macro('collective-ts', function() { 
            Preset::install();
        });
    }
}
