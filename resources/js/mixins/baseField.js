import { Localization } from 'laravel-nova';

export default {
    mixins: [ Localization ],
    data() {
        return {
            name:                    null,
            passwordLength:          this.field.passwordLength ?? 16,
            passwordTotalLength:     this.field.passwordTotalLength ?? null,
            passwordMin:             this.field.passwordMin ?? 8,
            passwordMax:             this.field.passwordMax ?? 128,
            passwordIncrementSteps:  this.field.passwordIncrementSteps ?? 4,
            passwordPrefix:          this.field.passwordPrefix ?? '',
            passwordSuffix:          this.field.passwordSuffix ?? '',
            customCharlist:          this.field.customCharlist ?? null,
            regenerateOnToggle:      this.field.regenerateOnToggle ?? true,
            fakeValue:               'V$3gx2Z#tjr2U*@k',
            dotValue:                '••••••••••••••••',
            showValueOnDetail:       this.field.showValueOnDetail ?? false,
            showValueOnIndex:        this.field.showValueOnIndex ?? false,
            blurValueOnDetail:       this.field.blurValueOnDetail ?? false,
            showValueOnUpdate:       this.field.showValueOnUpdate ?? false,
            blurValueOnIndex:        this.field.blurValueOnIndex ?? false,
            redactValueOnDetail:     this.field.redactValueOnDetail ?? false,
            redactValueOnIndex:      this.field.redactValueOnIndex ?? false,
            redactionCharacter:      this.field.redactionCharacter ?? '•',
            hideCopyPasswordButton:  this.field.hideCopyPasswordButton ?? false,
            hidePasswordLengthInput: this.field.hidePasswordLengthInput ?? false,
            hideShowPasswordToggle:  this.field.hideShowPasswordToggle ?? false,
            hideRegenerateButton:    this.field.hideRegenerateButton ?? false,
            hideOptionsToggles:      this.field.hideOptionsToggles ?? false,
            excluded:                {
                uppercase: this.field.excludeUppercase ?? false,
                lowercase: this.field.excludeLowercase ?? false,
                numbers:   this.field.excludeNumbers ?? false,
                symbols:   this.field.excludeSymbols ?? false,
                similar:   this.field.excludedSimilar ?? true,
                ambiguous: this.field.excludedAmbiguous ?? true,
            },
            charlists:               {
                uppercase:          'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                uppercaseNoSimilar: 'ABCDEFGHJKMNPQRSTUVWXYZ',
                lowercase:          'abcdefghijklmnopqrstuvwxyz',
                lowercaseNoSimilar: 'abcdefghjkmnpqrstuvwxyz',
                numbers:            '1234567890',
                numbersNoSimilar:   '23456789',
                symbols:            '`~\'"!@#$%^&*()_+-=[]{};:,.<>\\/|?',
                symbolsNoAmbiguous: '!@#$%^&*_+-=?',
            },
            classes:                 {
                enabled:      [
                    'border',
                    'border-gray-300',
                    'dark:border-gray-700',
                    'bg-primary-100',
                    'dark:bg-primary-500',
                    'text-primary-500',
                    'dark:text-gray-900',
                    'font-bold',
                    'text-sm',
                    'ooo:pg-enabled',
                ],
                disabled:     [
                    'border',
                    'border-gray-300',
                    'dark:border-gray-700',
                    'bg-gray-100',
                    'dark:bg-gray-800',
                    'font-bold',
                    'text-sm',
                    'ooo:pg-disabled',
                ],
                enabledPill:  [
                    'bg-primary-500',
                    'hover:bg-primary-400',
                    'active:bg-primary-600',
                    'dark:bg-primary-500',
                    'dark:hover:bg-primary-600',
                    'text-white',
                    'dark:text-gray-900',
                    // 'bg-gray-500',
                    // 'dark:bg-gray-600',
                    // 'text-white',
                    // 'dark:text-white',
                    'font-bold',
                    'text-sm',
                    'ooo:pg-enabled',
                ],
                disabledPill: [
                    'font-bold',
                    'text-sm',
                    'ooo:pg-disabled',
                ],
            },
            tooltips:                {
                showPassword:       {
                    enabled:  this.__( 'Show :Name', { name: this.__( this.field.name ) } ),
                    disabled: this.__( 'Hide :Name', { name: this.__( this.field.name ) } ),
                },
                uppercase:          {
                    enabled:  this.__( 'Exclude :Option', { option: this.__( 'Uppercase' ) } ),
                    disabled: this.__( 'Include :Option', { option: this.__( 'Uppercase' ) } ),
                },
                lowercase:          {
                    enabled:  this.__( 'Exclude :Option', { option: this.__( 'Lowercase' ) } ),
                    disabled: this.__( 'Include :Option', { option: this.__( 'Lowercase' ) } ),
                },
                numbers:            {
                    enabled:  this.__( 'Exclude :Option', { option: this.__( 'Numbers' ) } ),
                    disabled: this.__( 'Include :Option', { option: this.__( 'Numbers' ) } ),
                },
                symbols:            {
                    enabled:  this.__( 'Exclude :Option', { option: this.__( 'Symbols' ) } ),
                    disabled: this.__( 'Include :Option', { option: this.__( 'Symbols' ) } ),
                },
                decreaseLength:     this.__( 'Decrease :Option', { option: this.__( 'Length' ) } ),
                increaseLength:     this.__( 'Increase :Option', { option: this.__( 'Length' ) } ),
                copyPassword:       {
                    dynamic:  this.__( 'Copy :Name', { name: this.__( this.field.name ) } ),
                    enabled:  this.__( 'Copied!' ),
                    disabled: this.__( 'Copy :Name', { name: this.__( this.field.name ) } ),
                },
                regeneratePassword: this.__( 'Regenerate :Name', { name: this.__( this.field.name ) } ),
            },
        };
    },
    created() {
        let length     = this.passwordLength + this.passwordPrefix.length + this.passwordSuffix.length;
        this.fakeValue = this.generatePassword();
        this.dotValue  = this.repeatString( this.redactionCharacter, length );
        let uniqueKey  = this.field.uniqueKey;

        if ( uniqueKey.toLowerCase().includes( '-update-' ) ) {
            this.status = 'update';
        } else if ( uniqueKey.toLowerCase().includes( '-create-' ) ) {
            this.status = 'create';
        } else if ( uniqueKey.toLowerCase().includes( '-details-' ) ) {
            this.status = 'details';
        } else {
            this.status = 'index';
        }

        if ( this.customCharlist !== null && !this.hideOptionsToggles ) {
            this.hideOptionsToggles = true;
        }
    },
    methods: {
        generatePassword() {
            let password = '';
            let charlist = '';

            if ( this.validateToggles() ) {

                if ( this.customCharlist !== null ) {
                    charlist = this.customCharlist;
                } else {
                    if ( !this.excluded.uppercase && !this.excluded.similar ) charlist += this.charlists.uppercase;
                    if ( !this.excluded.uppercase && this.excluded.similar ) charlist += this.charlists.uppercaseNoSimilar;

                    if ( !this.excluded.lowercase && !this.excluded.similar ) charlist += this.charlists.lowercase;
                    if ( !this.excluded.lowercase && this.excluded.similar ) charlist += this.charlists.lowercaseNoSimilar;

                    if ( !this.excluded.numbers && !this.excluded.similar ) charlist += this.charlists.numbers;
                    if ( !this.excluded.numbers && this.excluded.similar ) charlist += this.charlists.numbersNoSimilar;

                    if ( !this.excluded.symbols && !this.excluded.ambiguous ) charlist += this.charlists.symbols;
                    if ( !this.excluded.symbols && this.excluded.ambiguous ) charlist += this.charlists.symbolsNoAmbiguous;
                }

                let adjustedLength = this.getLength();

                for ( let i = 1; i <= adjustedLength; i++ ) {
                    let character = Math.floor( Math.random() * charlist.length + 1 );
                    password += charlist.charAt( character );
                }

                if ( password.length < adjustedLength ) {
                    return this.generatePassword();
                }

                return this.passwordPrefix + password + this.passwordSuffix;
            }

            return this.value;
        },

        allExtrasHidden() {
            return this.hideShowPasswordToggle
                && this.hideOptionsToggles
                && this.hidePasswordLengthInput
                && this.hideCopyPasswordButton
                && this.hideRegenerateButton;
        },

        getLength() {
            let adjustedLength = this.passwordLength;

            if ( this.passwordTotalLength !== null ) {
                adjustedLength = this.passwordLength - this.passwordPrefix.length - this.passwordSuffix.length;
            }

            return adjustedLength;
        },

        repeatString( string, count ) {
            if ( count <= 0 ) return '';
            if ( count === 1 ) {
                return string;
            } else {
                return string + this.repeatString( string, count - 1 );
            }
        },

        validateToggles() {
            if ( this.excluded.uppercase
                && this.excluded.lowercase
                && this.excluded.numbers
                && this.excluded.symbols ) {
                Nova.error( this.__( 'Can\'t generate a :name if no options are enabled.', {
                    name: this.__( this.field.name ),
                } ) );
                return false;
            }

            return true;
        },

        increaseLength() {
            if ( this.passwordLength + this.passwordIncrementSteps <= this.passwordMax ) {
                this.passwordLength += this.passwordIncrementSteps;
                if ( this.regenerateOnToggle ) this.regeneratePassword();
            } else {
                Nova.error( this.__( 'Maximum generated :name length is: :length', {
                    name:   this.__( this.field.name ),
                    length: this.passwordMax,
                } ) );
            }
        },

        decreaseLength() {
            if ( this.passwordLength - this.passwordIncrementSteps >= this.passwordMin ) {
                this.passwordLength -= this.passwordIncrementSteps;
                if ( this.regenerateOnToggle ) this.regeneratePassword();
            } else {
                Nova.error( this.__( 'Minimum generated :name length is: :length', {
                    name:   this.__( this.field.name ),
                    length: this.passwordMin,
                } ) );
            }
        },

        toggleShowPassword() {
            this.showPassword = !this.showPassword;
        },

        toggleUppercase() {
            this.excluded.uppercase = !this.excluded.uppercase;
            if ( this.regenerateOnToggle ) this.regeneratePassword();
        },

        toggleLowercase() {
            this.excluded.lowercase = !this.excluded.lowercase;
            if ( this.regenerateOnToggle ) this.regeneratePassword();
        },

        toggleNumbers() {
            this.excluded.numbers = !this.excluded.numbers;
            if ( this.regenerateOnToggle ) this.regeneratePassword();
        },

        toggleSymbols() {
            this.excluded.symbols = !this.excluded.symbols;
            if ( this.regenerateOnToggle ) this.regeneratePassword();
        },
    },
};
