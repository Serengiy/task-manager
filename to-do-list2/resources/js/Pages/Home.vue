<template>
    <notifications position="left top" width="400" classes="my-notification"/>
    <div>

    </div>
    <Main :tags="tags" :lists="lists" :errors="errors" class="relative">
        <div class="py-2 pl-3 w-full mr-4 my_box h-screen overflow-y-auto" id="my_box">
            <div class="flex p-3 items-center mb-5">
                <h1 class="text-5xl font-bold">Today</h1>
                <h1 class="text-4xl font-bold border-2 rounded p-2 border-gray-200 ml-3">5 nov</h1>
            </div>
            <div class="w-full">
                <div class="mt-3">
                    <button @click="newTask" class="flex items-center text-left border border-gray-300 text-gray-600 rounded-lg block w-full p-2.5 hover:bg-gray-200 hover:text-gray-900"><i class="bx text-xl bx-plus mr-3"></i>Add new task</button>
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
                                            <p :class="`${tag.color} hover:border hover:border hover:border-gray-500 border cursor-pointer rounded-lg px-2 py-0.2`">{{tag.tag}}</p>
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
        <transition name="parent-slide"  class="relative">
            <div v-if="taskRedactor" class="flex py-2 pr-2">
                <div>
                    <div class="min-h-screen flex flex-row">
                        <div class="flex flex-col w-96 bg-gray-200 rounded-3xl overflow-hidden" id="my_slider">
                            <div class="p-4 font-bold text-gray-900 flex justify-between items-center">
                                <h1 class="text-3xl">Task:</h1>
                                <span @click="newTask" class="cursor-pointer"><i class="text-gray-500 text-3xl bx bx-x"></i></span>
                            </div>
                            <form class="flex flex-col items-center">
                                <div class="w-5/6 mb-4">
                                    <input v-model="form.title" type="text" class="w-full border-gray-300 rounded bg-gray-200" placeholder="task">
                                    <div v-if="errors">
                                        <p class="text-sm text-red-500 mt-1">{{errors.title}}</p>
                                    </div>
                                </div>
                                <div class="w-5/6 mb-4">
                                    <textarea v-model="form.content" rows="5" class="w-full border-gray-300 rounded bg-gray-200 resize-none" placeholder="description"></textarea>
                                    <div v-if="errors">
                                        <p class="text-sm text-red-500 mt-1">{{errors.content}}</p>
                                    </div>
                                </div>
                                <div v-if="lists" class="w-5/6 mb-4">
                                    <div v-if="lists.length > 0" class="w-5/6 mb-4">
                                        <label>List:</label>
                                        <MySelect required ref="listSelected" :data="lists.map(list => list.category)" :mode="'single'" :value="form.list"></MySelect>
                                        <div v-if="errors" class="w-5/6">
                                            <p class=" text-sm text-red-500 mt-1">{{errors.list}}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-5/6 flex justify-between items-center">
                                    <label for="dueDate">Due date</label>
                                    <input type="date" v-model="form.due_date" class="bg-gray-200 rounded border-gray-300 h-3/4">
                                </div>
                                <div v-if="errors" class="w-5/6">
                                    <p class=" text-sm text-red-500 mt-1">{{errors.due_date}}</p>
                                </div>
                                <div v-if="tags" class="w-5/6 mb-4">
                                    <div v-if="tags.length > 0" class="w-5/6 mb-4 mt-4">
                                        <p class="mb-2">Tags:</p>
                                        <MySelect required ref="tagsSelected" :data="tags.map(tag => tag.tag)" :mode="'tags'" :value="form.tags"></MySelect>
                                        <button @click.prevent="submit"></button>
                                        <div v-if="errors" class="w-5/6">
                                            <p class=" text-sm text-red-500 mt-1">{{errors.tags}}</p>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="!tags && !lists" class="p-4 h-full text-red-700 text-center flex items-end justify-between">
                                    <p>Add some lists and tags for start working with tasks</p>
                                </div>
                                <div v-if="tags && lists" class="p-4 h-full flex items-end justify-between">
                                    <button @click.prevent="nots" class="text-sm border border-gray-300 px-8 py-2 rounded-md hover:bg-gray-500 hover:text-white">Delete Task</button>
                                    <button v-if="editTask" @click.prevent="update" class=" ml-8 text-sm border border-gray-300 px-8 py-2 rounded-md bg-yellow-400 hover:bg-red-600 hover:text-white">Save changes</button>
                                    <button v-if="submitTask" @click.prevent="submit" class=" ml-8 text-sm border border-gray-300 px-8 py-2 rounded-md bg-yellow-400 hover:bg-red-600 hover:text-white">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </Main>
</template>

<script setup>
import Main from '@/Layouts/Main.vue';
import MySelect from "@/Components/MySelect.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import Checkbox from "@/Components/Checkbox.vue";
import { useNotification } from "@kyvg/vue3-notification";
import {reactive, ref} from "vue";

const taskRedactor = ref(false);

const editTask = ref(false);
const submitTask = ref(false);

const notification = useNotification()


const nots =()=>{
    notification.notify({
        title: "Vue 3 notification 🎉",
        text: "The operation completed",
        type: "success",
    });
}

const errors = reactive(ref(null))

const tags  = reactive(usePage().props.tags);
const lists  = reactive(usePage().props.lists);
const tasks = reactive(usePage().props.tasks)

// MySelect components
const tagsSelected = ref(null);
const listSelected = ref(null);

const form = useForm({
    due_date:new Date(),
    id: '',
    title:'',
    content:'',
    list:null,
    tags:[]
})

const submit = () =>{
    form.tags = tagsSelected.value.selectedValue
    form.list = listSelected.value.selectedValue
    form.post('/tasks', {
        onSuccess:(res) => {
            Object.assign(tasks, res.props.tasks)
            notification.notify({
                title: "Новая задача создана успешно 🎉",
                text: `Выпролнить до ${form.due_date}`,
                type: "success",
            });
            form.reset()
        },
        onError:(res) => {
            errors.value = res
        }
    })
}

const update = () => {
    form.tags = tagsSelected.value.selectedValue
    form.list = String(listSelected.value.selectedValue)
    form.patch(`/tasks/${form.id}`, {
        onSuccess:(res)=>{
            Object.assign(tasks, res.props.tasks)
            notification.notify({
                title: "Задача обновлена",
                text: `Выпролнить до ${form.due_date}`,
                type: "success",
            });
            form.reset()
        }
    })
}

const taskEdit = (task) =>{
    submitTask.value = false
    editTask.value = true
    taskRedactor.value = true
    form.id = task.id
    form.title = task.title
    form.content = task.content
    form.due_date = task.due_date.split('T')[0]
    form.tags = task.tags.map(tag => tag.tag)
    form.list = [task.category[0].category]
}
const newTask = () =>{
    errors.value = null
    form.reset()
    editTask.value = false
    submitTask.value = true
    taskRedactor.value = !taskRedactor.value;
}

const alertSuccess = () =>{

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
