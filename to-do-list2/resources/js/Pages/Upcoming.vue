<template>
    <notifications position="left top" width="400" classes="my-notification"/>
    <div>

    </div>
    <Main :tags="tags" :lists="lists" class="relative">
        <div class="py-2 pl-3 w-full mr-4 my_box h-screen overflow-y-auto">
            <div class="flex p-3 items-center mb-5">
                <h1 class="text-5xl font-bold">Upcoming</h1>
                <h1 class="text-4xl font-bold border-2 rounded p-2 border-gray-200 ml-3">{{new Date().toUTCString().slice(5,8)}}</h1>
            </div>
            <div class="h-full">
                <div class=" mr-4 border m-4 border-gray-300 rounded-xl ">
                    <TaskList class="overflow-y-auto" @newTask="newTask" @taskEdit="taskEdit" :tasks="todayTasks" :date="'Today'"></TaskList>
                </div>
                <div :class="isColumnClass ? 'flex h-1/2 ' : 'flex flex-col mr-8 '">
                    <div class="border w-full m-4 border-gray-300 rounded-xl overflow-y-auto">
                        <TaskList  @newTask="newTask" @taskEdit="taskEdit" :tasks="tomorrowTasks" :date="'Tomorrow'"></TaskList>
                    </div>
                    <div class="border w-full m-4 border-gray-300 rounded-xl overflow-y-auto">
                        <TaskList  @newTask="newTask" @taskEdit="taskEdit" :tasks="thisWeekTasks" :date="'This week'"></TaskList>
                    </div>
                </div>
            </div>
        </div>
        <TaskForm :taskRedactor="taskRedactor" :lists="lists" :tags="tags" @taskCreated="updateTasks"></TaskForm>
    </Main>
</template>

<script setup>
import Main from '@/Layouts/Main.vue';
import {useForm, usePage} from "@inertiajs/vue3";
import { useNotification } from "@kyvg/vue3-notification";
import {reactive, ref} from "vue";
import TaskList from "@/Components/TaskList.vue";
import TaskForm from "@/Components/TaskForm.vue";

const notification = useNotification()
const taskRedactor = reactive(ref({
    trigger: true,
    task:null,
    isOpened: false,
}));
const form = useForm({
  due_date:new Date(),
  id: '',
  title:'',
  content:'',
  list:null,
  tags:[]
})

const isColumnClass = ref(true);
const tags  = reactive(usePage().props.tags);
const lists  = reactive(usePage().props.lists);
const todayTasks = reactive(usePage().props.todayTasks)
const tomorrowTasks = reactive(usePage().props.tomorrowTasks)
const thisWeekTasks = reactive(usePage().props.thisWeekTasks)

const updateTasks = (newTasks) => {
  Object.assign(todayTasks, newTasks.todayTasks)
  Object.assign(tomorrowTasks, newTasks.tomorrowTasks)
  Object.assign(thisWeekTasks, newTasks.thisWeekTasks)
}
const taskEdit = (task) =>{
  taskRedactor.value.isOpened = true
  taskRedactor.value.task = task
  taskRedactor.value.trigger = !taskRedactor.value.trigger
  isColumnClass.value = !taskRedactor.value.isOpened;
}
const newTask = () =>{
  taskRedactor.value.isOpened = !taskRedactor.value.isOpened
  taskRedactor.value.trigger =!taskRedactor.value.trigger
  taskRedactor.value.task = null
  isColumnClass.value = !taskRedactor.value.isOpened;
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
