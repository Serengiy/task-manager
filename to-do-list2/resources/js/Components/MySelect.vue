<template>
    <div>
        <Multiselect
            v-model="selectedValue"
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
import {ref, watch} from "vue";

export default {
    components: {Multiselect},
    props: [
        'data',
        'mode',
        'value'
    ],
    setup(props) {
        const selectedValue = ref(props.value)
        const options = props.data;

        watch(
            () => props.value,
            (newValue) => {
                selectedValue.value = newValue;
            }
        );

        return {
            selectedValue,
            options,
        };
    }
}
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
