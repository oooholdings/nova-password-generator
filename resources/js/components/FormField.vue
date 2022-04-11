<template>
    <DefaultField :field="field"
                  :errors="errors"
                  :show-help-text="showHelpText"
                  class="password-generator"
                  :class="[ responsiveClasses, toolbarPositionClasses ]">
        <template #field>
            <input ref="passwordInput"
                   :id="field.attribute"
                   :type="showPassword ? 'text' : 'password'"
                   class="w-full form-control form-input form-input-bordered pg-password-input"
                   :class="[ errorClasses, passwordInputExtrasClasses ]"
                   :placeholder="field.name"
                   v-model="value" />
            <ul v-if="!allExtrasHidden()"
                class="pg-options">
                <li v-if="!hideShowPasswordToggle"
                    class="pg-option hover:text-primary-400 active:text-primary-600"
                    :class="showPassword ? classes.enabled : classes.disabled"
                    @click="toggleShowPassword"
                    v-tooltip="toggled.showPassword ? tooltips.showPassword.enabled : tooltips.showPassword.disabled">
                    <svg v-if="showPassword"
                         xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2.5">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                    <svg v-else
                         xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2.5">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </li>
                <li v-if="!hideOptionsToggles"
                    class="pg-option pg-pill-wrapper"
                    :class="classes.disabled">
                    <ul class="pg-pill-options">
                        <li class="pg-pill-option"
                            :class="toggled.lowercase ? classes.enabledPill : classes.disabledPill"
                            @click="toggleLowercase"
                            v-tooltip="toggled.lowercase ? tooltips.lowercase.enabled : tooltips.lowercase.disabled"
                            :style="[ borderRadiusRightStyles( toggled.lowercase && toggled.uppercase ) ]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2.5">
                                <circle cx="12"
                                        cy="12"
                                        r="6" />
                                <path d="m18 18v-12"
                                      stroke-linecap="round"
                                      stroke-linejoin="round" />
                            </svg>
                        </li>
                        <li class="pg-pill-option"
                            :class="toggled.uppercase ? classes.enabledPill : classes.disabledPill"
                            @click="toggleUppercase"
                            v-tooltip="toggled.uppercase ? tooltips.uppercase.enabled : tooltips.uppercase.disabled"
                            :style="[ borderRadiusLeftStyles( toggled.lowercase && toggled.uppercase ), borderRadiusRightStyles( toggled.uppercase && toggled.numbers ) ]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2.5"
                                 stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path d="m5 20 7-16 7 16" />
                                <path d="m16.832703 15.669922h-9.664673" />
                            </svg>
                        </li>
                        <li class="pg-pill-option"
                            :class="toggled.numbers ? classes.enabledPill : classes.disabledPill"
                            @click="toggleNumbers"
                            v-tooltip="toggled.numbers ? tooltips.numbers.enabled : tooltips.numbers.disabled"
                            :style="[ borderRadiusLeftStyles( toggled.uppercase && toggled.numbers ), borderRadiusRightStyles( toggled.numbers && toggled.symbols ) ]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2.5">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                            </svg>
                        </li>
                        <li class="pg-pill-option"
                            :class="toggled.symbols ? classes.enabledPill : classes.disabledPill"
                            @click="toggleSymbols"
                            v-tooltip="toggled.symbols ? tooltips.symbols.enabled : tooltips.symbols.disabled"
                            :style="[ borderRadiusLeftStyles( toggled.numbers && toggled.symbols ) ]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2.5">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </li>
                    </ul>
                </li>
                <li v-if="!hidePasswordLengthInput"
                    class="pg-option"
                    :class="classes.disabled">
                    <div class="pg-increment">
                        <div v-tooltip="tooltips.decreaseLength">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 style="width: 12px; height: 12px;"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="3"
                                 class="hover:text-primary-400 active:text-primary-600"
                                 @click="decreaseLength">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M20 12H4" />
                            </svg>
                        </div>

                        <div class="bg-transparent hover:bg-white focus:bg-white rounded pg-password-length">
                            {{ passwordLength }}
                        </div>

                        <div v-tooltip="tooltips.increaseLength">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 style="width: 12px; height: 12px;"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="3"
                                 class="hover:text-primary-400 active:text-primary-600"
                                 @click="increaseLength">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                    </div>
                </li>
                <li v-if="!hideCopyPasswordButton"
                    class="pg-option hover:text-primary-400 active:text-primary-600"
                    :class="classes.disabled"
                    v-tooltip="tooltips.copyPassword.dynamic"
                    @click="copyPassword">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2.5">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                    </svg>
                </li>
                <li v-if="!hideRegenerateButton"
                    class="pg-option hover:text-primary-400 active:text-primary-600"
                    :class="classes.disabled"
                    @click="regeneratePassword"
                    v-tooltip="tooltips.regeneratePassword">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2.5">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </li>
            </ul>
        </template>
    </DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';

export default {
    mixins: [ FormField, HandlesValidationErrors ],

    props: [ 'resourceName', 'resourceId', 'field' ],

    data() {
        return {
            status:                  null,
            toolbarOnTop:            this.field.toolbarOnTop ?? false,
            responsive:              this.field.responsive ?? true,
            passwordLength:          this.field.passwordLength ?? 16,
            passwordTotalLength:     this.field.passwordTotalLength ?? null,
            passwordMin:             this.field.passwordMin ?? 8,
            passwordMax:             this.field.passwordMax ?? 128,
            passwordIncrementSteps:  this.field.passwordIncrementSteps ?? 4,
            excludeSimilar:          this.field.excludeSimilar ?? true,
            excludeAmbiguous:        this.field.excludeAmbiguous ?? true,
            showPassword:            this.field.showPassword ?? false,
            fillOnCreate:            this.field.fillOnCreate ?? false,
            fillOnUpdate:            this.field.fillOnUpdate ?? false,
            hideCopyPasswordButton:  this.field.hideCopyPasswordButton ?? false,
            hidePasswordLengthInput: this.field.hidePasswordLengthInput ?? false,
            hideShowPasswordToggle:  this.field.hideShowPasswordToggle ?? false,
            hideRegenerateButton:    this.field.hideRegenerateButton ?? false,
            hideOptionsToggles:      this.field.hideOptionsToggles ?? false,
            regenerateOnToggle:      this.field.regenerateOnToggle ?? true,
            passwordPrefix:          this.field.passwordPrefix ?? '',
            passwordSuffix:          this.field.passwordSuffix ?? '',
            toggled:                 {
                lowercase: this.field.lowercaseToggled ?? true,
                uppercase: this.field.uppercaseToggled ?? true,
                numbers:   this.field.numbersToggled ?? true,
                symbols:   this.field.symbolsToggled ?? true,
            },
            tooltips:                {
                showPassword:       { enabled: 'Hide Password', disabled: 'Show Password' },
                lowercase:          { enabled: 'Exclude Lowercase', disabled: 'Include Lowercase' },
                uppercase:          { enabled: 'Exclude Uppercase', disabled: 'Include Uppercase' },
                numbers:            { enabled: 'Exclude Numbers', disabled: 'Include Numbers' },
                symbols:            { enabled: 'Exclude Symbols', disabled: 'Include Symbols' },
                decreaseLength:     'Decrease Length',
                increaseLength:     'Increase Length',
                copyPassword:       { dynamic: 'Copy Password', enabled: 'Copied!', disabled: 'Copy Password' },
                regeneratePassword: 'Regenerate Password',
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
                    'pg-enabled'
                ],
                disabled:     [
                    'border',
                    'border-gray-300',
                    'dark:border-gray-700',
                    'bg-gray-100',
                    'dark:bg-gray-800',
                    'font-bold',
                    'text-sm',
                    'pg-disabled'
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
                    'pg-enabled'
                ],
                disabledPill: [
                    'font-bold',
                    'text-sm',
                    'pg-disabled'
                ],
            },
            charlists:               {
                lowercase:          'abcdefghijklmnopqrstuvwxyz',
                lowercaseNoSimilar: 'abcdefghjkmnpqrstuvwxyz',
                uppercase:          'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                uppercaseNoSimilar: 'ABCDEFGHJKMNPQRSTUVWXYZ',
                numbers:            '1234567890',
                numbersNoSimilar:   '23456789',
                symbols:            '`~\'"!@#$%^&*()_+-=[]{};:,.<>\\/|?',
                symbolsNoAmbiguous: '!@#$%^&*_+-=?',
            }
        };
    },

    mounted() {
        // console.log( this.field );

        let panelName = this.field.panel;
        let uniqueKey = this.field.uniqueKey;

        if ( panelName.toLowerCase().includes( 'update' ) && uniqueKey.toLowerCase().includes( 'update' ) ) {
            this.status = 'update';
            if ( this.fillOnUpdate ) this.regeneratePassword();
        } else if ( panelName.toLowerCase().includes( 'create' ) && uniqueKey.toLowerCase().includes( 'create' ) ) {
            this.status = 'create';
            if ( this.fillOnCreate ) this.regeneratePassword();
        }
    },

    computed: {
        responsiveClasses() {
            return this.responsive
                ? [ 'pg-responsive' ]
                : [ 'pg-mobile-always' ];
        },

        toolbarPositionClasses() {
            return this.toolbarOnTop
                ? [ 'pg-toolbar-top' ]
                : [ 'pg-toolbar-bottom' ];
        },

        passwordInputExtrasClasses() {
            return this.allExtrasHidden()
                ? [ 'pg-extras-hidden' ]
                : [ 'pg-with-extras' ];
        },
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = '';
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill( formData ) {
            formData.append( this.field.attribute, this.value || '' );
        },

        borderRadiusLeftStyles( $bool ) {
            return $bool
                ? [ 'border-top-left-radius: 0;', 'border-bottom-left-radius: 0;' ]
                : [  ];
        },

        borderRadiusRightStyles( $bool ) {
            return $bool
                ? [ 'border-top-right-radius: 0;', 'border-bottom-right-radius: 0;' ]
                : [  ];
        },

        allExtrasHidden() {
            return this.hideShowPasswordToggle
                && this.hideOptionsToggles
                && this.hidePasswordLengthInput
                && this.hideCopyPasswordButton
                && this.hideRegenerateButton;
        },

        generatePassword() {
            let password = '';
            let charlist = '';

            if ( this.validateToggles() ) {
                if ( this.toggled.lowercase && !this.excludeSimilar ) charlist += this.charlists.lowercase;
                if ( this.toggled.lowercase && this.excludeSimilar ) charlist += this.charlists.lowercaseNoSimilar;

                if ( this.toggled.uppercase && !this.excludeSimilar ) charlist += this.charlists.uppercase;
                if ( this.toggled.uppercase && this.excludeSimilar ) charlist += this.charlists.uppercaseNoSimilar;

                if ( this.toggled.numbers && !this.excludeSimilar ) charlist += this.charlists.numbers;
                if ( this.toggled.numbers && this.excludeSimilar ) charlist += this.charlists.numbersNoSimilar;

                if ( this.toggled.symbols && !this.excludeAmbiguous ) charlist += this.charlists.symbols;
                if ( this.toggled.symbols && this.excludeAmbiguous ) charlist += this.charlists.symbolsNoAmbiguous;

                let adjustedLength = this.passwordLength;

                if ( this.passwordTotalLength !== null ) {
                    adjustedLength = this.passwordLength - this.passwordPrefix.length - this.passwordSuffix.length;
                }

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

        regeneratePassword() {
            this.value = this.generatePassword();
        },

        increaseLength() {
            if ( this.passwordLength + this.passwordIncrementSteps <= this.passwordMax ) {
                this.passwordLength += this.passwordIncrementSteps;
                if ( this.regenerateOnToggle ) this.regeneratePassword();
            } else {
                Nova.error( 'Maximum generated password length is: ' + this.passwordMax );
            }
        },

        decreaseLength() {
            if ( this.passwordLength - this.passwordIncrementSteps >= this.passwordMin ) {
                this.passwordLength -= this.passwordIncrementSteps;
                if ( this.regenerateOnToggle ) this.regeneratePassword();
            } else {
                Nova.error( 'Minimum generated password length is: ' + this.passwordMin );
            }
        },

        copyPassword() {
            if ( this.$refs.passwordInput.value !== '' ) {
                if ( navigator.clipboard && window.isSecureContext ) {
                    navigator.clipboard.writeText( this.value );
                    Nova.success( 'Password has been copied.' );
                } else {
                    let changedType = false;

                    if ( this.$refs.passwordInput.getAttribute( 'type' ) === 'password' ) {
                        this.$refs.passwordInput.setAttribute( 'type', 'text' );
                        changedType = true;
                    }

                    this.$refs.passwordInput.focus();
                    this.$refs.passwordInput.select();

                    return new Promise( ( res, rej ) => {
                        document.execCommand( 'copy' ) ? res() : rej();
                        Nova.success( 'Password has been copied.' );

                        if ( changedType ) {
                            this.$refs.passwordInput.setAttribute( 'type', 'password' );
                        }
                    } );
                }
            } else {
                Nova.error( 'No password to copy, type or generate a password.' );
            }
        },

        validateToggles() {
            if ( !this.toggled.lowercase
                && !this.toggled.uppercase
                && !this.toggled.numbers
                && !this.toggled.symbols ) {
                Nova.error( 'Can\'t generate a password if no options are enabled.' );
                return false;
            }

            return true;
        },

        toggleShowPassword() {
            this.showPassword = !this.showPassword;
        },

        toggleLowercase() {
            this.toggled.lowercase = !this.toggled.lowercase;
            if ( this.regenerateOnToggle ) this.regeneratePassword();
        },

        toggleUppercase() {
            this.toggled.uppercase = !this.toggled.uppercase;
            if ( this.regenerateOnToggle ) this.regeneratePassword();
        },

        toggleNumbers() {
            this.toggled.numbers = !this.toggled.numbers;
            if ( this.regenerateOnToggle ) this.regeneratePassword();
        },

        toggleSymbols() {
            this.toggled.symbols = !this.toggled.symbols;
            if ( this.regenerateOnToggle ) this.regeneratePassword();
        },
    },
}
</script>
