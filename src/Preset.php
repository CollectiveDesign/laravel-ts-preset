<?php

namespace Collective\LaravelTypescriptPreset;

use Illuminate\Foundation\Console\Presets\Preset as LaravelPreset;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Symfony\Component\Console\Output\ConsoleOutput;

class Preset extends LaravelPreset 
{

    public static function install() 
    {
        $output = new ConsoleOutput();
        static::updatePackages();
        $output->writeln('<info>package.json file updated.</info>');
        $output->writeln('<info>Installing npm dependencies...</info>');
        exec('npm install');
        $output->writeln('<info>Npm dependencies installed.</info>');
        static::updateMix();
        $output->writeln('<info>webpack.mix.js file updated.</info>');
        static::updateScripts();
        $output->writeln('<info>Scripts updated.</info>');
        static::updateStyles();
        $output->writeln('<info>Styles updated.</info>');
    }

    public static function updatePackageArray($packages) 
    {
        $requiredPackages = [
            "axios",
            "ts-loader",
            "typescript",
            "foundation-sites",
            "cross-env",
            "es6-promise",
            "laravel-mix",
            "sass",
            "sass-loader",
            "vue-template-compiler",
            "vue",
            "vuex",
            "vue-class-component",
            "vue-property-decorator",
            "vuex-module-decorators",
            "laravel-mix-purgecss"
        ];
        $dependencies = [];
        $client = new Client();
        foreach($requiredPackages as $requiredPackage) {
            try {
                $result = $client->get('https://registry.npmjs.org/'.$requiredPackage.'/latest');
                if($result->getStatusCode() === 200) {
                    $result = json_decode($result->getBody());
                    $dependencies[$requiredPackage] = '^'.$result->version;
                }
            } catch (GuzzleException $e) {
                dd($e->getRequest());
            }
        }
        return $dependencies;
    }

    public static function updateMix() 
    {
        File::copy(__DIR__.'/stubs/js/webpack.mix.js', base_path('webpack.mix.js'));
    }

    public static function updateScripts() 
    {
        File::delete(resource_path('js/app.js'));
        File::delete(resource_path('js/bootstrap.js'));
        File::cleanDirectory(resource_path('js/components'));
        File::copy(__DIR__.'/stubs/js/app.ts', resource_path('js/app.ts'));
        File::copy(__DIR__.'/stubs/js/vue-shims.d.ts', resource_path('js/vue-shims.d.ts'));
        File::copy(__DIR__.'/stubs/js/tsconfig.json', base_path('tsconfig.json'));
    }

    public static function updateStyles() 
    {
        File::cleanDirectory(resource_path('sass'));
        File::copy(base_path('node_modules/foundation-sites/scss/settings/_settings.scss'), resource_path('sass/_settings.scss'));
        $content = file_get_contents(resource_path('sass/_settings.scss'));
        $content = preg_replace("~@import 'util/util';~", " ", $content);
        file_put_contents(resource_path('sass/_settings.scss'), $content);
        File::copy(__DIR__.'/stubs/scss/app.scss', resource_path('sass/app.scss'));
        File::copy(__DIR__.'/stubs/scss/_fonts.scss', resource_path('sass/_fonts.scss'));
    }

}