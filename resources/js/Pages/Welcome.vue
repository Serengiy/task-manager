<template>
    <Main :tags="tags" :lists="lists">
        <div class="py-2 pl-3 w-full mr-4 my_box" id="my_box" :class="{ 'smooth': taskRedactor}">
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
                                    <div class="flex items-center text-xs gap-1">
                                        <div :class="`${task.category[0].color}  rounded w-3 h-3`"></div>
                                        <p>{{task.category[0].category}}</p>
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
                            <i class="bx text-xl text-gray-500 bx-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <transition name="parent-slide">
            <div  v-if="taskRedactor" class="flex py-2 pr-2">
                <div>
                    <div class="min-h-screen flex flex-row ">
                        <div class="flex flex-col w-96 bg-gray-200 rounded-3xl overflow-hidden" >
                            <div class="p-4 font-bold text-gray-900 flex justify-between items-center">
                                <h1 class="text-3xl">Task:</h1>
                                <span @click="newTask" class="cursor-pointer"><i class="text-gray-500 text-3xl bx bx-x"></i></span>
                            </div>
                            <form class="flex flex-col items-center">
                                <div class="w-5/6 mb-4">
                                    <input v-model="form.title" type="text" class="w-full border-gray-300 rounded bg-gray-200" placeholder="task">
                                    <div v-if="$page.props.errors.title">
                                        <p class="text-sm text-red-500 mt-1">{{$page.props.errors.title}}</p>
                                    </div>
                                </div>
                                <div class="w-5/6 mb-4">
                                    <textarea v-model="form.content" rows="5" class="w-full border-gray-300 rounded bg-gray-200 resize-none" placeholder="description"></textarea>
                                    <div v-if="$page.props.errors.content">
                                        <p class="text-sm text-red-500 mt-1">{{$page.props.errors.content}}</p>
                                    </div>
                                </div>
                                <div v-if="lists.length > 0" class="w-5/6 mb-4">
                                    <label>List:</label>
                                    <Select required ref="listSelected" :data="lists.map(list => list.category)" :mode="'single'" :value="form.list"></Select>
                                    <div v-if="$page.props.errors.list" class="w-5/6">
                                        <p class=" text-sm text-red-500 mt-1">{{$page.props.errors.list}}</p>
                                    </div>
                                </div>
                                <div class="w-5/6 flex justify-between items-center">
                                    <label for="dueDate">Due date</label>
                                    <input type="date" v-model="form.due_date" class="bg-gray-200 rounded border-gray-300 h-3/4">
                                </div>
                                <div v-if="$page.props.errors.due_date" class="w-5/6">
                                    <p class=" text-sm text-red-500 mt-1">{{$page.props.errors.due_date}}</p>
                                </div>
                                <div v-if="tags.length > 0" class="w-5/6 mb-4 mt-4">
                                    <p class="mb-2">Tags:</p>
                                    <Select required ref="tagsSelected" :data="tags.map(tag => tag.tag)" :mode="'tags'" :value="form.tags"></Select>
                                    <div v-if="$page.props.errors.tags" class="w-5/6">
                                        <p class=" text-sm text-red-500 mt-1">{{$page.props.errors.tags}}</p>
                                    </div>
                                </div>
                                <div v-if="tags.length <= 0 && lists.length <= 0" class="p-4 h-full text-red-700 text-center flex items-end justify-between">
                                    <p>Add some lists and tags for start working with tasks</p>
                                </div>
                                <div v-if="tags.length > 0 && lists.length > 0" class="p-4 h-full flex items-end justify-between">
                                    <button class="text-sm border border-gray-300 px-8 py-2 rounded-md hover:bg-gray-500 hover:text-white">Delete Task</button>
                                        <button v-if="editButton" @click.prevent="update" class=" ml-8 text-sm border border-gray-300 px-8 py-2 rounded-md bg-yellow-400 hover:bg-red-600 hover:text-white">Save Changes</button>
                                        <button v-if="submitButton" @click.prevent="submit" class=" ml-8 text-sm border border-gray-300 px-8 py-2 rounded-md bg-yellow-400 hover:bg-red-600 hover:text-white">Submit</button>
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
import {ref} from 'vue';
import Main from '@/Layouts/Main.vue';
import {useForm, usePage} from "@inertiajs/vue3";
import Select from "@/Components/Select.vue";
import Checkbox from "@/Components/Checkbox.vue";
import {list} from "postcss";
import {createLogger} from "vite";

const taskRedactor = ref(false);
const editButton = ref(false);
const submitButton = ref(false);

const tagsSelected = ref(null);
const listSelected = ref(null);

// const category = reactive({value:''});
const tags  = usePage().props.tags;
const lists  = usePage().props.lists;
const tasks = usePage().props.tasks

const form = useForm({
    due_date:new Date(),
    id: '',
    title:'',
    content:'',
    list:[],
    tags:null
})

const update = () =>{
    form.patch(`/task/${form.id}`, {
        onSuccess:()=>{
            form.reset()
            listSelected.value.value = []
            tagsSelected.value.value = []
        }
    })
}
const submit = () =>{
    // console.log(form)
    // form.post('/task', {
    //     onSuccess:() => {
    //         form.reset()
    //         listSelected.value.value = []
    //         tagsSelected.value.value = []
    //     }
    // })
}
const taskEdit = (task) =>{
    submitButton.value = false
    editButton.value = true
    taskRedactor.value = true
    form.id = task.id
    form.title = task.title
    form.content = task.content
    form.due_date = task.due_date.split('T')[0]
    listSelected.value.value = [task.category[0].category]
    tagsSelected.value.value = task.tags.map(tag => tag.tag)
    form.list = [task.category[0].category]
    form.tags = task.tags.map(tag => tag.tag)
}

const newTask = () =>{
    form.reset()
    editButton.value = false
    submitButton.value = true
    taskRedactor.value = !taskRedactor.value;
}
const taskToggle = () => {
    form.reset()
    taskRedactor.value = !taskRedactor.value;
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
</style>
