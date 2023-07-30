<template>
    <div>
        <Multiselect
            v-model="value"
            :mode="mode"
            placeholder="Select"
            :options="options"
            :search="false"
            style="background: #e6e7eb"
        />
    </div>
</template>

<script>
import Multiselect from '@vueform/multiselect'

export default {
    components: { Multiselect },
    props:[
        'data',
        'mode',
        'value'
    ],
    data() {
        return {
            value: this.value,
            options: this.data
        }
    }
}
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
