<template>
    <notifications position="left top" width="400" classes="my-notification"/>
    <div>

    </div>
    <Main :tags="tags" :lists="lists" class="relative">
        <div class="py-2 pl-3 w-full mr-4 my_box h-screen overflow-y-auto" id="my_box">
            <div class="flex p-3 items-center mb-5">
<!--                <h1 class="text-5xl font-bold">Today</h1>-->
                <h1 class="text-4xl font-bold border-2 rounded p-2 border-gray-200 ml-3">{{pageTitle}}</h1>
            </div>
            <div class="w-full">
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
                                            <p >{{task.category[0].category}}</p>
<!--                                            <Link :href="route('home', {tag: tag.tag})" :class="`${tag.color} hover:border hover:border-gray-500 border cursor-pointer rounded-lg px-2 py-0.2`">{{tag.tag}}</Link>-->
                                        </div>
                                    </div>
                                    <div class="flex">
                                        <div v-for="tag in task.tags" class="flex items-center text-xs gap-1">
                                            <!--                                        <div :class="`${task.category[0].color}  rounded w-3 h-3`"></div>-->
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
        <TaskForm :taskRedactor="taskRedactor" :lists="lists" :tags="tags" @taskCreated="updateTasks"></TaskForm>
    </Main>
</template>

<script setup>
import Main from '@/Layouts/Main.vue';
import MySelect from "@/Components/MySelect.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import Checkbox from "@/Components/Checkbox.vue";
import { useNotification } from "@kyvg/vue3-notification";
import {reactive, ref} from "vue";
import {Link} from "@inertiajs/vue3";
import TaskForm from "@/Components/TaskForm.vue";

const notification = useNotification()
const taskRedactor = reactive(ref({
    trigger: true,
    task:null,
    isOpened: false,
}));

const pageTitle = ref(usePage().props.title)
const tags  = reactive(usePage().props.tags);
const lists  = reactive(usePage().props.lists);
const tasks = reactive(usePage().props.tasks)


const form = useForm({
    due_date:new Date(),
    id: '',
    title:'',
    content:'',
    list:null,
    tags:[]
})

const updateTasks = (newTasks) => {
    Object.assign(tasks, newTasks.tasks)
}

const taskEdit = (task) =>{
    taskRedactor.value.isOpened = true
    taskRedactor.value.task = task
    taskRedactor.value.trigger = !taskRedactor.value.trigger
}
const newTask = () =>{
    taskRedactor.value.isOpened = !taskRedactor.value.isOpened
    taskRedactor.value.trigger =!taskRedactor.value.trigger
    taskRedactor.value.task = null
}
</script>

<style>
.parent-slide-enter-active,
.parent-slide-leave-active {
    transition: transform 0.3s ease;
}
.parent-slide-enter-from,
.parent-slide-leave-to {
    transform: translateX(100%);
}

.my-notification {
    margin: 0 5px 5px;
    padding: 15px;
    font-size: 16px;
    color: #ffffff;

    background: #44a4fc;
    border-left: 5px solid #187fe7;

    &.success {
        background: #68cd86;
        border-left-color: #42a85f;
    }

    &.warn {
        background: #ffb648;
        border-left-color: #f48a06;
    }

    &.error {
        background: #e54d42;
        border-left-color: #b82e24;
    }
}
</style>
