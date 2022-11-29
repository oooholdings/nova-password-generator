<?php

namespace OutOfOffice\PasswordGenerator;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Component identifier name.
     *
     * @var string
     */
    public static string $name = 'password-generator';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../resources/lang/' => resource_path('lang/vendor/'
                . static::$name),
        ]);

        Nova::serving(function (ServingNova $event) {
            Nova::script(static::$name, __DIR__ . '/../dist/js/field.js');
            Nova::style(static::$name, __DIR__ . '/../dist/css/field.css');
            Nova::translations(static::getTranslations());
        });
    }

    /**
     * Get the translation keys from file.
     *
     * @return array
     */
    private static function getTranslations(): array
    {
        $translationFile = resource_path('lang/vendor/' . static::$name . '/'
            . app()->getLocale() . '.json');

        if ( ! is_readable($translationFile)) {
            $translationFile = __DIR__ . '/../resources/lang/'
                . app()->getLocale() . '.json';

            if ( ! is_readable($translationFile)) {
                return [];
            }
        }

        return json_decode(file_get_contents($translationFile), true);
    }
}
