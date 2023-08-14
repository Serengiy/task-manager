<script setup>

import MySelect from "@/Components/MySelect.vue";
import {useForm} from "@inertiajs/vue3";
import {ref, watch, defineEmits} from "vue";
import {useNotification} from "@kyvg/vue3-notification";


const props = defineProps({
  taskRedactor: Object,
  lists:Object,
  tags:Object,
})
const form = useForm({
    due_date:null,
    id: '',
    title:'',
    content:'',
    list:null,
    tags:[]
})
const notification = useNotification()
const taskRedactor = ref(props.taskRedactor.isOpened);
const tagsSelected = ref(null);
const listSelected = ref(null);
const errors = ref(null);

const emit = defineEmits();
const editTaskBtn = ref(false);


const submitTaskBtn = ref(false);
const taskManager = () =>{
    props.taskRedactor.task ? taskEdit(props.taskRedactor.task) : newTask()
}
watch(()=> props.taskRedactor.trigger, taskManager)
const newTask = ()=>{
    form.reset()
    editTaskBtn.value = false
    submitTaskBtn.value = true
    taskRedactor.value = !taskRedactor.value
}
const taskEdit = (task) =>{
    submitTaskBtn.value = false
    editTaskBtn.value = true
    taskRedactor.value = true
    form.id = task.id
    form.title = task.title
    form.content = task.content
    form.due_date = task.due_date.split('T')[0]
    form.tags = task.tags.map(tag => tag.tag)
    form.list = [task.category[0].category]

}

const submit = () =>{
    form.tags = tagsSelected.value.selectedValue
    form.list = listSelected.value.selectedValue
    form.post('/tasks', {
    onSuccess:(res) => {
      errors.value = null
      emit('taskCreated', res.props);
      notification.notify({
        title: "Новая задача создана успешно 🎉",
        text: `Выпоолнить до ${form.due_date}`,
        type: "success",
      });
      form.reset()
    },
    onError:(res) => {
      errors.value = res
      console.log(res)
    }
  })
}

const update = () => {
  form.tags = tagsSelected.value.selectedValue
  form.list = String(listSelected.value.selectedValue)
  form.patch(`/tasks/${form.id}`, {
    onSuccess:(res)=>{
        console.log(res)
      errors.value = null
      emit('taskCreated', res.props);
      notification.notify({
        title: "Задача обновлена",
        text: `Выпоолнить до ${form.due_date}`,
        type: "success",
      });
      form.reset()
    },
    onError:(res) =>{
        errors.value = res
    }
  })
}



//

</script>

<template>
  <notifications position="left top" width="400" classes="my-notification"/>
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
                <button @click.prevent="" class="text-sm border border-gray-300 px-8 py-2 rounded-md hover:bg-gray-500 hover:text-white">Delete Task</button>
                <button v-if="editTaskBtn" @click.prevent="update" class=" ml-8 text-sm border border-gray-300 px-8 py-2 rounded-md bg-yellow-400 hover:bg-red-600 hover:text-white">Save changes</button>
                <button v-if="submitTaskBtn" @click.prevent="submit" class=" ml-8 text-sm border border-gray-300 px-8 py-2 rounded-md bg-yellow-400 hover:bg-red-600 hover:text-white">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<style scoped>

</style>
