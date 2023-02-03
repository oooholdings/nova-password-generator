<template>
    <DefaultField :field="field"
                  :errors="errors"
                  :show-help-text="showHelpText">
        <template #field>
            <div class="ooo:password-generator"
                 :class="[ responsiveClasses, toolbarPositionClasses ]">
                <input ref="passwordInput"
                       :id="field.attribute"
                       :type="showPassword ? 'text' : 'password'"
                       class="w-full form-control form-input form-input-bordered ooo:pg-password-input"
                       :class="[ errorClasses, passwordInputExtrasClasses ]"
                       :placeholder="field.name"
                       v-model="value" />
                <ul v-if="!allExtrasHidden()"
                    class="ooo:pg-options">
                    <li v-if="!hideShowPasswordToggle"
                        class="ooo:pg-option hover:text-primary-400 active:text-primary-600"
                        :class="showPassword ? classes.enabled : classes.disabled"
                        @click="toggleShowPassword"
                        v-tooltip="showPassword ? tooltips.showPassword.disabled : tooltips.showPassword.enabled">
                        <icon-hide v-if="showPassword"
                                   class="h-4 w-4"></icon-hide>
                        <icon-show v-else
                                   class="h-4 w-4"></icon-show>
                    </li>
                    <li v-if="!hideOptionsToggles"
                        class="ooo:pg-option ooo:pg-pill-wrapper"
                        :class="classes.disabled">
                        <ul class="ooo:pg-pill-options">
                            <li class="ooo:pg-pill-option"
                                :class="!excluded.uppercase ? classes.enabledPill : classes.disabledPill"
                                @click="toggleUppercase"
                                v-tooltip="!excluded.uppercase ? tooltips.uppercase.enabled : tooltips.uppercase.disabled"
                                :style="[ borderRadiusRightStyles( !excluded.uppercase && !excluded.lowercase ) ]">
                                <icon-uppercase class="h-4 w-4"></icon-uppercase>
                            </li>
                            <li class="ooo:pg-pill-option"
                                :class="!excluded.lowercase ? classes.enabledPill : classes.disabledPill"
                                @click="toggleLowercase"
                                v-tooltip="!excluded.lowercase ? tooltips.lowercase.enabled : tooltips.lowercase.disabled"
                                :style="[ borderRadiusLeftStyles( !excluded.uppercase && !excluded.lowercase ), borderRadiusRightStyles( !excluded.lowercase && !excluded.numbers ) ]">
                                <icon-lowercase class="h-4 w-4"></icon-lowercase>
                            </li>
                            <li class="ooo:pg-pill-option"
                                :class="!excluded.numbers ? classes.enabledPill : classes.disabledPill"
                                @click="toggleNumbers"
                                v-tooltip="!excluded.numbers ? tooltips.numbers.enabled : tooltips.numbers.disabled"
                                :style="[ borderRadiusLeftStyles( !excluded.lowercase && !excluded.numbers ), borderRadiusRightStyles( !excluded.numbers && !excluded.symbols ) ]">
                                <icon-hashtag class="h-4 w-4"></icon-hashtag>
                            </li>
                            <li class="ooo:pg-pill-option"
                                :class="!excluded.symbols ? classes.enabledPill : classes.disabledPill"
                                @click="toggleSymbols"
                                v-tooltip="!excluded.symbols ? tooltips.symbols.enabled : tooltips.symbols.disabled"
                                :style="[ borderRadiusLeftStyles( !excluded.numbers && !excluded.symbols ) ]">
                                <icon-at class="h-4 w-4"></icon-at>
                            </li>
                        </ul>
                    </li>
                    <li v-if="!hidePasswordLengthInput"
                        class="ooo:pg-option"
                        :class="classes.disabled">
                        <div class="ooo:pg-increment">
                            <div v-tooltip="tooltips.decreaseLength">
                                <icon-minus style="width: 12px; height: 12px;"
                                            class="hover:text-primary-400 active:text-primary-600"
                                            @click="decreaseLength"></icon-minus>
                            </div>

                            <div class="bg-transparent hover:bg-white focus:bg-white rounded ooo:pg-password-length">
                                {{ passwordLength }}
                            </div>

                            <div v-tooltip="tooltips.increaseLength">
                                <icon-plus style="width: 12px; height: 12px;"
                                           class="hover:text-primary-400 active:text-primary-600"
                                           @click="increaseLength"></icon-plus>
                            </div>
                        </div>
                    </li>
                    <li v-if="!hideCopyPasswordButton"
                        class="ooo:pg-option hover:text-primary-400 active:text-primary-600"
                        :class="classes.disabled"
                        v-tooltip="tooltips.copyPassword.dynamic"
                        @click="copyPassword">
                        <icon-copy class="h-4 w-4"></icon-copy>
                    </li>
                    <li v-if="!hideRegenerateButton"
                        class="ooo:pg-option hover:text-primary-400 active:text-primary-600"
                        :class="classes.disabled"
                        @click="regeneratePassword"
                        v-tooltip="tooltips.regeneratePassword">
                        <icon-refresh class="h-4 w-4"></icon-refresh>
                    </li>
                </ul>
            </div>
        </template>
    </DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import baseField from '../mixins/baseField';
import atIcon from './icons/atIcon';
import copyIcon from './icons/copyIcon';
import hashtagIcon from './icons/hashtagIcon';
import hideIcon from './icons/hideIcon';
import lowercaseIcon from './icons/lowercaseIcon';
import minusIcon from './icons/minusIcon';
import plusIcon from './icons/plusIcon';
import refreshIcon from './icons/refreshIcon';
import showIcon from './icons/showIcon';
import uppercaseIcon from './icons/uppercaseIcon';

export default {
    mixins:     [ FormField, HandlesValidationErrors, baseField ],
    props:      [ 'resourceName', 'resourceId', 'field' ],
    components: {
        'icon-hide':      hideIcon,
        'icon-show':      showIcon,
        'icon-uppercase': uppercaseIcon,
        'icon-lowercase': lowercaseIcon,
        'icon-hashtag':   hashtagIcon,
        'icon-at':        atIcon,
        'icon-minus':     minusIcon,
        'icon-plus':      plusIcon,
        'icon-copy':      copyIcon,
        'icon-refresh':   refreshIcon,
    },
    data() {
        return {
            status:       null,
            toolbarOnTop: this.field.toolbarOnTop ?? false,
            responsive:   this.field.responsive ?? true,
            showPassword: this.field.showPassword ?? false,
            fillOnCreate: this.field.fillOnCreate ?? false,
            fillOnUpdate: this.field.fillOnUpdate ?? false,
        };
    },
    mounted() {
        if ( this.fillOnUpdate && this.status === 'update' ) this.regeneratePassword();
        if ( this.fillOnCreate && this.status === 'create' ) this.regeneratePassword();
    },
    computed: {
        responsiveClasses() {
            return this.responsive
                ? [ 'ooo:pg-responsive' ]
                : [ 'ooo:pg-mobile-always' ];
        },

        toolbarPositionClasses() {
            return this.toolbarOnTop
                ? [ 'ooo:pg-toolbar-top' ]
                : [ 'ooo:pg-toolbar-bottom' ];
        },

        passwordInputExtrasClasses() {
            return this.allExtrasHidden()
                ? [ 'ooo:pg-extras-hidden' ]
                : [ 'ooo:pg-with-extras' ];
        },
    },
    methods:  {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.showValueOnUpdate
                ? this.field.value
                : null;
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
                : [];
        },

        borderRadiusRightStyles( $bool ) {
            return $bool
                ? [ 'border-top-right-radius: 0;', 'border-bottom-right-radius: 0;' ]
                : [];
        },

        regeneratePassword() {
            this.value = this.generatePassword();
        },

        copyPassword() {
            if ( this.$refs.passwordInput.value !== '' ) {
                if ( navigator.clipboard && window.isSecureContext ) {
                    navigator.clipboard.writeText( this.value );
                    Nova.success( this.__( ':Name has been copied.', { name: this.__( this.field.name ) } ) );
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
                        Nova.success( this.__( ':Name has been copied.', { name: this.__( this.field.name ) } ) );

                        if ( changedType ) {
                            this.$refs.passwordInput.setAttribute( 'type', 'password' );
                        }
                    } );
                }
            } else {
                Nova.error( this.__( 'Nothing to copy, type or generate a :name.', {
                    name: this.__( this.field.name ),
                } ) );
            }
        },
    },
};
</script>
