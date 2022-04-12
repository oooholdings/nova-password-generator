<?php

namespace OutOfOffice\PasswordGenerator;

use Illuminate\Support\Facades\Hash;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class PasswordGenerator extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'password-generator';

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param NovaRequest $request
     * @param string      $requestAttribute
     * @param object      $model
     * @param string      $attribute
     * @return void
     */
    protected function fillAttributeFromRequest( NovaRequest $request, $requestAttribute, $model, $attribute )
    {
        if ( $request->exists( $requestAttribute ) ) {
            $model->{$attribute} = Hash::make( $request[ $requestAttribute ] );
        }
    }

    /**
     * Set the length of the generated password.
     *
     * @param int $length
     * @return PasswordGenerator
     */
    public function length( int $length = 16 ): PasswordGenerator
    {
        return $this->withMeta( [
            'passwordLength' => $length,
        ] );
    }

    /**
     * Set the total length of the generated password
     * respecting the prefix and suffix.
     *
     * @param int $length
     * @return PasswordGenerator
     */
    public function totalLength( int $length = 24 ): PasswordGenerator
    {
        return $this->withMeta( [
            'passwordTotalLength' => $length,
        ] );
    }

    /**
     * Set the minimum length of the generated password.
     *
     * @param int $minLength
     * @return PasswordGenerator
     */
    public function minLength( int $minLength = 8 ): PasswordGenerator
    {
        return $this->withMeta( [
            'passwordMin' => $minLength,
        ] );
    }

    /**
     * Set the maximum length of the generated password.
     *
     * @param int $maxLength
     * @return PasswordGenerator
     */
    public function maxLength( int $maxLength = 128 ): PasswordGenerator
    {
        return $this->withMeta( [
            'passwordMax' => $maxLength,
        ] );
    }

    /**
     * Set the step increment of the generated password.
     *
     * @param int $lengthSteps
     * @return PasswordGenerator
     */
    public function lengthIncrementSteps( int $lengthSteps = 4 ): PasswordGenerator
    {
        return $this->withMeta( [
            'passwordIncrementSteps' => $lengthSteps,
        ] );
    }

    /**
     * Add prefix to the generated passwords.
     *
     * @param string $prefix
     * @return PasswordGenerator
     */
    public function prefix( string $prefix = '' ): PasswordGenerator
    {
        return $this->withMeta( [
            'passwordPrefix' => $prefix,
        ] );
    }

    /**
     * Add suffix to the generated passwords.
     *
     * @param string $suffix
     * @return PasswordGenerator
     */
    public function suffix( string $suffix = '' ): PasswordGenerator
    {
        return $this->withMeta( [
            'passwordSuffix' => $suffix,
        ] );
    }

    /**
     * Hide copy password button in password field ui.
     *
     * @param bool $hide
     * @return PasswordGenerator
     */
    public function hideAllExtras( bool $hide = true ): PasswordGenerator
    {
        return $this->withMeta( [
            'hideShowPasswordToggle'  => $hide,
            'hideOptionsToggles'      => $hide,
            'hidePasswordLengthInput' => $hide,
            'hideCopyPasswordButton'  => $hide,
            'hideRegenerateButton'    => $hide,
        ] );
    }

    /**
     * Hide show password toggle in password field ui.
     *
     * @param bool $hide
     * @return PasswordGenerator
     */
    public function hideShowPasswordToggle( bool $hide = true ): PasswordGenerator
    {
        return $this->withMeta( [
            'hideShowPasswordToggle' => $hide,
        ] );
    }

    /**
     * Hide password options toggles in password field ui.
     *
     * @param bool $hide
     * @return PasswordGenerator
     */
    public function hideOptionsToggles( bool $hide = true ): PasswordGenerator
    {
        return $this->withMeta( [
            'hideOptionsToggles' => $hide,
        ] );
    }

    /**
     * Hide password length input in password field ui..
     *
     * @param bool $hide
     * @return PasswordGenerator
     */
    public function hideLengthInput( bool $hide = true ): PasswordGenerator
    {
        return $this->withMeta( [
            'hidePasswordLengthInput' => $hide,
        ] );
    }

    /**
     * Hide copy password button in password field ui.
     *
     * @param bool $hide
     * @return PasswordGenerator
     */
    public function hideCopyPasswordButton( bool $hide = true ): PasswordGenerator
    {
        return $this->withMeta( [
            'hideCopyPasswordButton' => $hide,
        ] );
    }

    /**
     * Hide regenerate password button in password field ui.
     *
     * @param bool $hide
     * @return PasswordGenerator
     */
    public function hideRegenerateButton( bool $hide = true ): PasswordGenerator
    {
        return $this->withMeta( [
            'hideRegenerateButton' => $hide,
        ] );
    }

    /**
     * Exclude lowercase characters in password charlist
     *
     * @param bool $exclude
     * @return PasswordGenerator
     */
    public function excludeLowercase( bool $exclude = true ): PasswordGenerator
    {
        return $this->withMeta( [
            'excludeLowercase' => $exclude,
        ] );
    }

    /**
     * Exclude uppercase characters in password charlist
     *
     * @param bool $exclude
     * @return PasswordGenerator
     */
    public function excludeUppercase( bool $exclude = true ): PasswordGenerator
    {
        return $this->withMeta( [
            'excludeUppercase' => $exclude,
        ] );
    }

    /**
     * Exclude number characters in password charlist
     *
     * @param bool $exclude
     * @return PasswordGenerator
     */
    public function excludeNumbers( bool $exclude = true ): PasswordGenerator
    {
        return $this->withMeta( [
            'excludeNumbers' => $exclude,
        ] );
    }

    /**
     * Exclude symbol characters in password charlist.
     *
     * @param bool $exclude
     * @return PasswordGenerator
     */
    public function excludeSymbols( bool $exclude = true ): PasswordGenerator
    {
        return $this->withMeta( [
            'excludeSymbols' => $exclude,
        ] );
    }

    /**
     * Exclude similar characters in password charlist.
     * e.g. "i, l, 1, L, o, 0, O"
     *
     * @param bool $exclude
     * @return PasswordGenerator
     */
    public function excludeSimilar( bool $exclude = true ): PasswordGenerator
    {
        return $this->withMeta( [
            'excludeSimilar' => $exclude,
        ] );
    }

    /**
     * Exclude ambiguous symbols in password charlist.
     * e.g. "{ } [ ] ( ) / \ ' " ` ~ , ; : . < >"
     *
     * @param bool $exclude
     * @return PasswordGenerator
     */
    public function excludeAmbiguous( bool $exclude = true ): PasswordGenerator
    {
        return $this->withMeta( [
            'excludeAmbiguous' => $exclude,
        ] );
    }

    /**
     * Exclude multiple options at once.
     *
     * @param array $excludeRules
     * @return PasswordGenerator
     */
    public function excludeRules( array $excludeRules ): PasswordGenerator
    {
        return $this->withMeta( [
            'excludeLowercase' => !empty( array_intersect( [ 'lowercase', 'lower' ], $excludeRules ) ),
            'excludeUppercase' => !empty( array_intersect( [ 'uppercase', 'upper' ], $excludeRules ) ),
            'excludeNumbers'   => !empty( array_intersect( [ 'numbers', 'digits' ], $excludeRules ) ),
            'excludeSymbols'   => !empty( array_intersect( [ 'symbols', 'special' ], $excludeRules ) ),
            'excludeSimilar'   => !empty( array_intersect( [ 'similar' ], $excludeRules ) ),
            'excludeAmbiguous' => !empty( array_intersect( [ 'ambiguous' ], $excludeRules ) ),
        ] );
    }

    /**
     * Fill password field with generated password when creating resource.
     *
     * @param bool $enabled
     * @return PasswordGenerator
     */
    public function fillOnCreate( bool $enabled = true ): PasswordGenerator
    {
        return $this->withMeta( [
            'fillOnCreate' => $enabled,
        ] );
    }

    /**
     * Fill password field with generated password when updating resource.
     *
     * @param bool $enabled
     * @return PasswordGenerator
     */
    public function fillOnUpdate( bool $enabled = true ): PasswordGenerator
    {
        return $this->withMeta( [
            'fillOnUpdate' => $enabled,
        ] );
    }

    /**
     * Disable password hiding by default.
     *
     * @param bool $show
     * @return PasswordGenerator
     */
    public function showPassword( bool $show = true ): PasswordGenerator
    {
        return $this->withMeta( [
            'showPassword' => $show,
        ] );
    }

    /**
     * Whether to regenerate the password when an option is toggled.
     *
     * @param bool $enabled
     * @return PasswordGenerator
     */
    public function regenerateOnToggle( bool $enabled = true ): PasswordGenerator
    {
        return $this->withMeta( [
            'regenerateOnToggle' => $enabled,
        ] );
    }

    /**
     * Whether the toolbar should be shown above the password input field.
     *
     * @param bool $toolbarOnTop
     * @return PasswordGenerator
     */
    public function toolbarOnTop( bool $toolbarOnTop = true ): PasswordGenerator
    {
        return $this->withMeta( [
            'toolbarOnTop' => $toolbarOnTop,
        ] );
    }

    /**
     * Whether the password field should be responsive.
     *
     * @param bool $mobileFirstAlways
     * @return PasswordGenerator
     */
    public function mobileFirstAlways( bool $mobileFirstAlways = true ): PasswordGenerator
    {
        return $this->withMeta( [
            'responsive' => !$mobileFirstAlways,
        ] );
    }
}
