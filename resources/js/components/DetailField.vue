<template>
    <PanelItem :index="index"
               :field="{ name: field.name }"
               class="ooo:password-generator">
        <template v-slot:value>
            <div v-if="showValueOnDetail || blurValueOnDetail || redactValueOnDetail"
                 class="ooo:pg-value">
                <div v-if="hidden"
                     class="top-0 left-0 inline-block font-mono mr-2"
                     :class="[ blurValueOnDetail ? 'ooo:pg-value-blurred' : '' ]">
                    {{ blurValueOnDetail ? fakeValue : dotValue }}
                </div>
                <div v-else
                     class="top-0 left-0 inline-block font-mono mr-2">
                    {{ field.value }}
                </div>

                <div class="flex">
                    <div v-if="!showValueOnDetail || blurValueOnDetail || redactValueOnDetail">
                        <icon-show v-if="hidden && !hideShowPasswordToggle"
                                   class="h-4 w-4"
                                   v-tooltip="tooltips.showPassword.enabled"
                                   @click="showValue"></icon-show>
                        <icon-hide v-else
                                   class="h-4 w-4"
                                   v-tooltip="tooltips.showPassword.disabled"
                                   @click="hideValue"></icon-hide>
                    </div>

                    <icon-copy v-if="!hideCopyPasswordButton"
                               class="h-4 w-4 ml-2"
                               v-tooltip="tooltips.copyPassword.dynamic"
                               @click="copyPassword"></icon-copy>
                </div>
            </div>
            <div v-else>&mdash;</div>
        </template>
    </PanelItem>
</template>

<script>
import baseField from '../mixins/baseField';
import hideIcon from './icons/hideIcon';
import showIcon from './icons/showIcon';
import copyIcon from './icons/copyIcon';

export default {
    mixins:     [ baseField ],
    props:      [ 'index', 'resource', 'resourceName', 'resourceId', 'field' ],
    components: {
        'icon-copy': copyIcon,
        'icon-hide': hideIcon,
        'icon-show': showIcon,
    },
    data() {
        return {
            hidden: !this.field.showValueOnDetail,
        };
    },
    mounted() {
        console.log( this.field );
    },
    methods: {
        showValue() {
            this.hidden = false;
        },
        hideValue() {
            this.hidden = true;
        },
        toggleValueVisibility() {
            this.hidden = !this.hidden;
        },
        copyPassword() {
            if ( this.field.value !== '' ) {
                if ( navigator.clipboard && window.isSecureContext ) {
                    navigator.clipboard.writeText( this.field.value );
                    Nova.success( this.__( ':Name has been copied.', { name: this.__( this.field.name ) } ) );
                } else {
                    let textArea   = document.createElement( 'textarea' );
                    textArea.value = this.field.value;

                    textArea.style.top      = '-9999px';
                    textArea.style.left     = '-9999px';
                    textArea.style.position = 'fixed';

                    document.body.appendChild( textArea );
                    textArea.focus();
                    textArea.select();

                    return new Promise( ( res, rej ) => {
                        document.execCommand( 'copy' ) ? res() : rej();
                        Nova.success( this.__( ':Name has been copied.', { name: this.__( this.field.name ) } ) );

                        textArea.remove();
                    } );
                }
            } else {
                Nova.error( this.__( 'Nothing to copy, type or generate a :name.', {
                    name: this.__( this.field.name )
                } ) );
            }
        },
    }
}
</script>
