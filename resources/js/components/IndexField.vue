<template>
    <div v-if="showValueOnIndex || blurValueOnIndex || redactValueOnIndex"
         class="ooo:pg-value select-none">
        <div v-if="hidden"
             class="top-0 left-0 inline-block font-mono mr-2"
             :class="[ blurValueOnIndex ? 'ooo:pg-value-blurred' : '' ]">
            {{ blurValueOnIndex ? fakeValue : dotValue }}
        </div>
        <div v-else
             class="top-0 left-0 inline-block font-mono mr-2">
            {{ field.value }}
        </div>
    </div>
    <div v-else>&mdash;</div>
</template>

<script>
import baseField from '../mixins/baseField';

export default {
    mixins:     [ baseField ],
    props:      [ 'resourceName', 'field' ],
    data() {
        return {
            hidden: !this.field.showValueOnIndex,
        };
    },
    mounted() {
        console.log( this.field );
    },
}
</script>

<style lang="scss">
.ooo\:pg-value {
    position        : relative;
    display         : inline-flex;
    justify-content : center;
    align-items     : center;

    .ooo\:pg-value-blurred {
        filter      : blur(3px);
        user-select : none;
    }
}
</style>
