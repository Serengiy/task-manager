<script setup>

import Main from "@/Layouts/Main.vue";
import MyCard from "@/Components/MyCard.vue";
import {reactive, ref} from "vue";
import {useForm, usePage, router} from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";

const modalTitle = ref('New sticker')
const isNewStickerBtn = ref(true)
const tags  = reactive(usePage().props.tags);
const lists  = reactive(usePage().props.lists);
const stickers = ref(usePage().props.stickers)
const errors = reactive(ref(null));
const form = useForm({
    id:'',
    title: '',
    content: '',
    color: 'bg-fuchsia-300',
})

const deleteSticker = (stickerId) =>{
    router.delete(`/sticker-wall/${stickerId}`, {
        onSuccess:(res) =>{
            stickers.value = res.props.stickers
        }
    })
}

const stickerEdit = (data) =>{
    modalTitle.value = 'Edit sticker'
    form.id = data.id
    form.title = data.title
    form.content = data.content
    form.color = data.color
    isNewStickerBtn.value = false
    modalIsOpened.value = true
}

const submit = () =>{
    form.post('sticker-wall', {
        onSuccess:(res)=>{
            form.reset()
            stickers.value = res.props.stickers
            modalClose()
        },
        onError:(res)=>{
            errors.value = res
        }
    })

}

const update = () =>{
    form.patch(`/sticker-wall/${form.id}`, {
        onSuccess:(res)=>{
            form.reset()
            stickers.value = res.props.stickers
            modalClose()
        }
    })
}

const modalIsOpened = reactive(ref(false))
const modalClose = () => {
    modalIsOpened.value = false
}
const newSticker = () =>{
    modalTitle.value = 'New sticker'
    modalIsOpened.value = true
    isNewStickerBtn.value = true
}

const setTagColor= (e)=>{
    form.color = e.srcElement.className.split(' ')[0]
}

</script>

<template>
  <Main :tags="tags" :lists="lists" class="relative" >
    <Modal :show="modalIsOpened" @close=modalClose>
      <div class="">
        <div class="text-right mt-4 mr-4">
            <a href="#"><i @click="modalClose" class="bx bxs-circle text-2xl text-red-500 cursor-pointer"></i></a>
        </div>
          <h2 class="text-center text-2xl text-gray-700 mb-6">{{modalTitle}}</h2>
        <form class="flex flex-col items-center mb-4">
          <div class="w-4/6 mb-4">
            <input v-model="form.title" type="text" class="w-full border-gray-300 rounded bg-gray-200" placeholder="title">
              <div v-if="errors && errors.title">
                  <p class=" mt-2 text-sm text-red-500">{{errors.title}}</p>
              </div>
          </div>
          <div class="w-4/6 mb-4">
            <textarea v-model="form.content" placeholder="note" rows="5" class="w-full border-gray-300 rounded bg-gray-200"></textarea>
              <div v-if="errors && errors.content">
                  <p class="text-sm text-red-500">{{errors.content}}</p>
              </div>
          </div>
            <div class="w-4/6 mb-4 flex items-center gap-2">
                <p class="text-gray-700">Sticker color:</p>
                <div :class="`${form.color} w-4 h-4 rounded-full`"></div>
            </div>
            <div class="flex flex-wrap gap-4 w-full mb-3 justify-center">
                <div @click="setTagColor" class="bg-fuchsia-300 cursor-pointer w-4 h-4 rounded-full"></div>
                <div @click="setTagColor" class="bg-indigo-300 cursor-pointer w-4 h-4 rounded-full"></div>
                <div @click="setTagColor" class="bg-amber-300 cursor-pointer w-4 h-4 rounded-full"></div>
                <div @click="setTagColor" class="bg-orange-300 cursor-pointer w-4 h-4 rounded-full"></div>
                <div @click="setTagColor" class="bg-emerald-300 cursor-pointer w-4 h-4 rounded-full"></div>
            </div>
        </form>
        <div class="flex justify-center">
          <div class="w-4/6 flex justify-between mb-6 ">
            <button @click.prevent="modalClose" class="px-4 py-2 bg-purple-200 rounded">Close</button>
            <button v-if="isNewStickerBtn" @click.prevent="submit" class="px-4 py-2 bg-purple-200 rounded">Save</button>
            <button v-if="!isNewStickerBtn" @click.prevent="update" class="px-4 py-2 bg-purple-200 rounded">Update</button>
          </div>
        </div>
      </div>
    </Modal>
    <div class="py-2 pl-3 w-full  mr-4 my_box h-screen overflow-y-auto" id="my_box">
      <div class="flex p-3 items-center mb-5 justify-between">
        <h1 class="text-4xl font-bold p-2  ml-3">Sticker Wall</h1>
        <div>
          <a href="#" @click.prevent="newSticker" class="mr-4">Add</a>
        </div>
      </div>
        <div class="h-5/6 border m-4 border-gray-300 rounded-xl overflow-y-auto">
            <div v-if="stickers" class="p-4 flex flex-wrap w-full justify-start">
                <MyCard @getStickerId="deleteSticker" @stickerEdit="stickerEdit" v-for="sticker in stickers" :key="sticker.id" :data="sticker" class=" m-2  sm:min-w-full"></MyCard>
            </div>
        </div>
    </div>
  </Main>

</template>

<style scoped>
textarea{
  resize: none;
}
.m-2 {
    margin: 0.5rem;
}
.flex-grow {
    flex-grow: 1; /* Allow the card to expand and take available space */
    max-width: calc(33.333% - 1rem); /* Calculate the maximum width to ensure full width for each card */
    min-width: 200px;
}



</style>
