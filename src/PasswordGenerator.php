<?php

namespace OutOfOffice\PasswordGenerator;

use Illuminate\Support\Facades\Hash;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class PasswordGenerator extends Field
{
    /**
     * Character Lists For Your Convenience
     */
    public const UPPERCASE    = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public const UPPERCASE_NS = 'ABCDEFGHJKMNPQRSTUVWXYZ';
    public const LOWERCASE    = 'abcdefghijklmnopqrstuvwxyz';
    public const LOWERCASE_NS = 'abcdefghjkmnpqrstuvwxyz';
    public const BASE10       = '1234567890';
    public const BASE16       = 'ABCDEF' . self::BASE10;
    public const BASE16_MOD   = 'ABCDEFabcdef' . self::BASE10;
    public const BASE32       = self::UPPERCASE . '234567';
    public const BASE36       = self::UPPERCASE . self::BASE10;
    public const BASE45_NS    = self::UPPERCASE . self::BASE10 . '_$%*+-./:';
    public const BASE58       = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz123456789';
    public const BASE62
                              = self::UPPERCASE . self::LOWERCASE
        . self::BASE10;
    public const BASE64       = self::BASE62 . '+/';
    public const NUMBERS      = self::BASE10;
    public const NUMBERS_NS   = '23456789';
    public const SYMBOLS      = '`~\'"!@#$%^&*()_+-=[]{};:,.<>\/|?';
    public const SYMBOLS_NA   = '!@#$%^&*_+-=?';

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
     *
     * @return void
     */
    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {
        if ($request->exists($requestAttribute)) {
            if ($request[$requestAttribute]) {
                if (isset($this->meta['saveAsPlainText'])
                    && $this->meta['saveAsPlainText']
                ) {
                    $model->{$attribute} = $request[$requestAttribute];
                } else {
                    $model->{$attribute}
                        = Hash::make($request[$requestAttribute]);
                }
            }
        }
    }

    /**
     * Set the length of the generated password.
     *
     * @param int $length
     *
     * @return PasswordGenerator
     */
    public function length(int $length = 16): PasswordGenerator
    {
        return $this->withMeta([
            'passwordLength' => $length,
        ]);
    }

    /**
     * Set the total length of the generated password
     * respecting the prefix and suffix.
     *
     * @param int $length
     *
     * @return PasswordGenerator
     */
    public function totalLength(int $length = 24): PasswordGenerator
    {
        return $this->withMeta([
            'passwordTotalLength' => $length,
        ]);
    }

    /**
     * Set the minimum length of the generated password.
     *
     * @param int $minLength
     *
     * @return PasswordGenerator
     */
    public function minLength(int $minLength = 8): PasswordGenerator
    {
        return $this->withMeta([
            'passwordMin' => $minLength,
        ]);
    }

    /**
     * Set the maximum length of the generated password.
     *
     * @param int $maxLength
     *
     * @return PasswordGenerator
     */
    public function maxLength(int $maxLength = 128): PasswordGenerator
    {
        return $this->withMeta([
            'passwordMax' => $maxLength,
        ]);
    }

    /**
     * Set the step increment of the generated password.
     *
     * @param int $lengthSteps
     *
     * @return PasswordGenerator
     */
    public function lengthIncrementSteps(int $lengthSteps = 4
    ): PasswordGenerator {
        return $this->withMeta([
            'passwordIncrementSteps' => $lengthSteps,
        ]);
    }

    /**
     * Add prefix to the generated passwords.
     *
     * @param string $prefix
     *
     * @return PasswordGenerator
     */
    public function prefix(string $prefix = ''): PasswordGenerator
    {
        return $this->withMeta([
            'passwordPrefix' => $prefix,
        ]);
    }

    /**
     * Add suffix to the generated passwords.
     *
     * @param string $suffix
     *
     * @return PasswordGenerator
     */
    public function suffix(string $suffix = ''): PasswordGenerator
    {
        return $this->withMeta([
            'passwordSuffix' => $suffix,
        ]);
    }

    /**
     * Hide copy password button in password field ui.
     *
     * @param bool $hide
     *
     * @return PasswordGenerator
     */
    public function hideAllExtras(bool $hide = true): PasswordGenerator
    {
        return $this->withMeta([
            'hideShowPasswordToggle'  => $hide,
            'hideOptionsToggles'      => $hide,
            'hidePasswordLengthInput' => $hide,
            'hideCopyPasswordButton'  => $hide,
            'hideRegenerateButton'    => $hide,
        ]);
    }

    /**
     * Hide show password toggle in password field ui.
     *
     * @param bool $hide
     *
     * @return PasswordGenerator
     */
    public function hideShowPasswordToggle(bool $hide = true): PasswordGenerator
    {
        return $this->withMeta([
            'hideShowPasswordToggle' => $hide,
        ]);
    }

    /**
     * Hide password options toggles in password field ui.
     *
     * @param bool $hide
     *
     * @return PasswordGenerator
     */
    public function hideOptionsToggles(bool $hide = true): PasswordGenerator
    {
        return $this->withMeta([
            'hideOptionsToggles' => $hide,
        ]);
    }

    /**
     * Hide password length input in password field ui..
     *
     * @param bool $hide
     *
     * @return PasswordGenerator
     */
    public function hideLengthInput(bool $hide = true): PasswordGenerator
    {
        return $this->withMeta([
            'hidePasswordLengthInput' => $hide,
        ]);
    }

    /**
     * Hide copy password button in password field ui.
     *
     * @param bool $hide
     *
     * @return PasswordGenerator
     */
    public function hideCopyPasswordButton(bool $hide = true): PasswordGenerator
    {
        return $this->withMeta([
            'hideCopyPasswordButton' => $hide,
        ]);
    }

    /**
     * Hide regenerate password button in password field ui.
     *
     * @param bool $hide
     *
     * @return PasswordGenerator
     */
    public function hideRegenerateButton(bool $hide = true): PasswordGenerator
    {
        return $this->withMeta([
            'hideRegenerateButton' => $hide,
        ]);
    }

    /**
     * Exclude lowercase characters in password charlist
     *
     * @param bool $exclude
     *
     * @return PasswordGenerator
     */
    public function excludeLowercase(bool $exclude = true): PasswordGenerator
    {
        return $this->withMeta([
            'excludeLowercase' => $exclude,
        ]);
    }

    /**
     * Exclude uppercase characters in password charlist
     *
     * @param bool $exclude
     *
     * @return PasswordGenerator
     */
    public function excludeUppercase(bool $exclude = true): PasswordGenerator
    {
        return $this->withMeta([
            'excludeUppercase' => $exclude,
        ]);
    }

    /**
     * Exclude number characters in password charlist
     *
     * @param bool $exclude
     *
     * @return PasswordGenerator
     */
    public function excludeNumbers(bool $exclude = true): PasswordGenerator
    {
        return $this->withMeta([
            'excludeNumbers' => $exclude,
        ]);
    }

    /**
     * Exclude symbol characters in password charlist.
     *
     * @param bool $exclude
     *
     * @return PasswordGenerator
     */
    public function excludeSymbols(bool $exclude = true): PasswordGenerator
    {
        return $this->withMeta([
            'excludeSymbols' => $exclude,
        ]);
    }

    /**
     * Exclude similar characters in password charlist.
     * e.g. "i, l, 1, L, o, 0, O"
     *
     * @param bool $exclude
     *
     * @return PasswordGenerator
     */
    public function excludeSimilar(bool $exclude = true): PasswordGenerator
    {
        return $this->withMeta([
            'excludeSimilar' => $exclude,
        ]);
    }

    /**
     * Exclude ambiguous symbols in password charlist.
     * e.g. "{ } [ ] ( ) / \ ' " ` ~ , ; : . < >"
     *
     * @param bool $exclude
     *
     * @return PasswordGenerator
     */
    public function excludeAmbiguous(bool $exclude = true): PasswordGenerator
    {
        return $this->withMeta([
            'excludeAmbiguous' => $exclude,
        ]);
    }

    /**
     * Exclude multiple options at once.
     *
     * @param array $excludeRules
     *
     * @return PasswordGenerator
     */
    public function excludeRules(array $excludeRules): PasswordGenerator
    {
        return $this->withMeta([
            'excludeLowercase' => ! empty(array_intersect([
                'lowercase',
                'lower',
            ], $excludeRules)),
            'excludeUppercase' => ! empty(array_intersect([
                'uppercase',
                'upper',
            ], $excludeRules)),
            'excludeNumbers'   => ! empty(array_intersect(['numbers', 'digits'],
                $excludeRules)),
            'excludeSymbols'   => ! empty(array_intersect([
                'symbols',
                'special',
            ], $excludeRules)),
            'excludeSimilar'   => ! empty(array_intersect(['similar'],
                $excludeRules)),
            'excludeAmbiguous' => ! empty(array_intersect(['ambiguous'],
                $excludeRules)),
        ]);
    }

    /**
     * Ignore built-in charlist for a custom one.
     * Also hides password options element on toolbar.
     *
     * @param string $charlist
     *
     * @return PasswordGenerator
     */
    public function customCharlist(
        string $charlist = PasswordGenerator::BASE16_MOD
    ): PasswordGenerator {
        return $this->withMeta([
            'customCharlist'     => $charlist,
            'hideOptionsToggles' => true,
        ]);
    }

    /**
     * Fill password field with generated password when creating resource.
     *
     * @param bool $enabled
     *
     * @return PasswordGenerator
     */
    public function fillOnCreate(bool $enabled = true): PasswordGenerator
    {
        return $this->withMeta([
            'fillOnCreate' => $enabled,
        ]);
    }

    /**
     * Fill password field with generated password when updating resource.
     *
     * @param bool $enabled
     *
     * @return PasswordGenerator
     */
    public function fillOnUpdate(bool $enabled = true): PasswordGenerator
    {
        return $this->withMeta([
            'fillOnUpdate' => $enabled,
        ]);
    }

    /**
     * Disable password hiding by default.
     *
     * @param bool $show
     *
     * @return PasswordGenerator
     */
    public function showPassword(bool $show = true): PasswordGenerator
    {
        return $this->withMeta([
            'showPassword' => $show,
        ]);
    }

    /**
     * Whether to regenerate the password when an option is toggled.
     *
     * @param bool $enabled
     *
     * @return PasswordGenerator
     */
    public function regenerateOnToggle(bool $enabled = true): PasswordGenerator
    {
        return $this->withMeta([
            'regenerateOnToggle' => $enabled,
        ]);
    }

    /**
     * Whether the toolbar should be shown above the password input field.
     *
     * @param bool $toolbarOnTop
     *
     * @return PasswordGenerator
     */
    public function toolbarOnTop(bool $toolbarOnTop = true): PasswordGenerator
    {
        return $this->withMeta([
            'toolbarOnTop' => $toolbarOnTop,
        ]);
    }

    /**
     * Disable the side toolbar, only displaying it above
     * or below the password field.
     *
     * @param bool $disable
     *
     * @return PasswordGenerator
     */
    public function disableSideToolbar(bool $disable = true): PasswordGenerator
    {
        return $this->withMeta([
            'responsive' => ! $disable,
        ]);
    }

    /**
     * Show the field value by default on detail view.
     *
     * @param bool $show
     *
     * @return PasswordGenerator
     */
    public function showValueOnDetail(bool $show = true): PasswordGenerator
    {
        return $this->withMeta([
            'showValueOnDetail' => $show,
        ]);
    }

    /**
     * Show the field value by default on index view.
     *
     * @param bool $show
     *
     * @return PasswordGenerator
     */
    public function showValueOnIndex(bool $show = true): PasswordGenerator
    {
        return $this->withMeta([
            'showValueOnIndex' => $show,
        ]);
    }

    /**
     * Show the field value by default on update view.
     *
     * @param bool $show
     *
     * @return PasswordGenerator
     */
    public function showValueOnUpdate(bool $show = true): PasswordGenerator
    {
        return $this->withMeta([
            'showValueOnUpdate' => $show,
        ]);
    }

    /**
     * Blur the field value by default on detail view.
     *
     * @param bool $blur
     *
     * @return PasswordGenerator
     */
    public function blurValueOnDetail(bool $blur = true): PasswordGenerator
    {
        return $this->withMeta([
            'blurValueOnDetail' => $blur,
        ]);
    }

    /**
     * Blur the field value by default on index view.
     *
     * @param bool $blur
     *
     * @return PasswordGenerator
     */
    public function blurValueOnIndex(bool $blur = true): PasswordGenerator
    {
        return $this->withMeta([
            'blurValueOnIndex' => $blur,
        ]);
    }

    /**
     * Redact the field value by default on detail view.
     *
     * @param bool   $redact
     * @param string $character
     *
     * @return PasswordGenerator
     */
    public function redactValueOnDetail(
        bool $redact = true,
        string $character = '•'
    ): PasswordGenerator {
        return $this->withMeta([
            'redactValueOnDetail' => $redact,
            'redactionCharacter'  => $character,
        ]);
    }

    /**
     * Redact the field value by default on index view.
     *
     * @param bool   $redact
     * @param string $character
     *
     * @return PasswordGenerator
     */
    public function redactValueOnIndex(
        bool $redact = true,
        string $character = '•'
    ): PasswordGenerator {
        return $this->withMeta([
            'redactValueOnIndex' => $redact,
            'redactionCharacter' => $character,
        ]);
    }

    /**
     * Disable hashing the provided value when filling attribute.
     *
     * @param bool $plainText
     *
     * @return PasswordGenerator
     */
    public function saveAsPlainText(bool $plainText = true): PasswordGenerator
    {
        return $this->withMeta([
            'saveAsPlainText' => $plainText,
        ]);
    }
}
