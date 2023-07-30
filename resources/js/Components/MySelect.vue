<template>
    <div class="ring-gray-3000">
        <select  multiple class="w-full ring-gray-300">
            <option v-for="option in tags" :key="option.value" :value="option.value">{{ option.tag }}</option>
        </select>
    </div>
</template>

<script>
export default {
    name: "MySelect",
    props:[
        'tags',
    ]
}
</script>

<style scoped>
/* Use ::v-deep to target the select dropdown's native styles */
::v-deep select {
    /* Change the background color */
    background-color: #f2f2f2;

    /* Change the text color */
    color: #333;

    /* Add other custom styles as needed */
}
</style>
