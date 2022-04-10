<template>
    <PanelItem :index="index"
               :field="{ name: field.name }" />
</template>

<script>
export default {
    props: [ 'index', 'resource', 'resourceName', 'resourceId', 'field' ],
}
</script>
