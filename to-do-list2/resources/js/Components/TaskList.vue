<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import {reactive, defineEmits} from "vue";
import {Link} from "@inertiajs/vue3";



// const tasks = reactive(usePage().props.tasks)


const emit = defineEmits();

const props = defineProps({
    date: String,
    tasks: Object
});

const newTask = () =>{
    emit('newTask')
}

const taskEdit = (task) =>{
    emit('taskEdit', task)
}

</script>

<template>
    <div class="">
        <div class="w-full p-4">
            <h1 class="text-2xl font-bold">{{date}}</h1>
            <div class="mt-3">
                <button @click="newTask" class="items-center text-left border border-gray-300 text-gray-600 rounded-lg block w-full p-2.5 hover:bg-gray-200 hover:text-gray-900"><i class="bx text-xl bx-plus mr-3"></i>Add new task</button>
            </div>
            <div v-if="tasks" class="mt-3">
                <div v-for="task in tasks" class="flex justify-between my-2 pb-2 border-b">
                    <div class="flex items-center gap-2 w-full">
                        <Checkbox checked="false"></Checkbox>
                        <div>
                            <p>{{task.title}}</p>
                            <!--                                <p>{{task}}</p>-->
                            <div class="flex gap-4">
                                <p class="text-xs flex items-center gap-1"><i class="bx bx-calendar"></i>{{ task.due_date.split('T')[0] }}</p>
                                <div v-if="task.category[0]">
                                    <div class="flex items-center text-xs gap-1">
                                        <div :class="`${task.category[0].color}  rounded w-3 h-3`"></div>
                                        <p>{{task.category[0].category}}</p>
                                    </div>
                                </div>
                                <div class="flex">
                                    <div v-for="tag in task.tags" class="flex items-center text-xs gap-1">
                                        <!--                                        <div :class="`${task.category[0].color}  rounded w-3 h-3`"></div>-->
<!--                                        <p :class="`${tag.color} hover:border hover:border-gray-500 border cursor-pointer rounded-lg px-2 py-0.2`">{{tag.tag}}</p>-->
                                        <Link :href="route('home', {tag: tag.tag})" :class="`${tag.color} hover:border hover:border-gray-500 border cursor-pointer rounded-lg px-2 py-0.2`">{{tag.tag}}</Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div @click.prevent="taskEdit(task)" class="w-1/6 text-right cursor-pointer flex items-center justify-end">
                        <i class="bx text-xl text-gray-500 bx-chevron-right mr-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
