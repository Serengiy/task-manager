<script setup>

import {defineEmits, reactive, ref} from "vue";
import Modal from "@/Components/Modal.vue";

const props = defineProps({
    data:Object,
})
const emit = defineEmits();

const stickerId = ref(null)
const showCross = ref(false)

const deleteTask = () =>{
    emit('getStickerId', stickerId.value)
    modalIsOpened.value = false
}

const stickerEdit = (data) =>{
    emit('stickerEdit', data)
}

const openModal = (e) =>{
    modalIsOpened.value = true
    stickerId.value = e
}

const modalIsOpened = reactive(ref(false))
const modalClose = () => {
    modalIsOpened.value = false
}

</script>

<template>
    <Modal :show="modalIsOpened" @close=modalClose>
        <div class="p-5 flex items-center flex-col">
            <h1 class="text-2xl text-center text-red-700">You sure you want to delete sticker?</h1>
            <div class="flex justify-between w-3/4  mt-5">
                <button @click.prevent="modalClose" class="border bg-green-600 text-white text-lg rounded py-1 px-7 hover:border hover:bg-white hover:border-green-600 hover:text-black">Cancel</button>
                <button @click.prevent="deleteTask" class="border bg-red-600 text-white text-lg rounded py-1 px-7 hover:border hover:bg-white hover:border-red-600 hover:text-black">Delete</button>
            </div>
        </div>
    </Modal>
  <div id="card" :class="`${data.color} relative m-2 p-3 rounded-lg w-72 h-72 text-gray-700 custom-scroll overflow-y-auto overflow-hidden`" @mouseover="showCross = true" @mouseout="showCross = false">

      <a href="#" @click.prevent="stickerEdit(data)" class="hover:scale-110 transition-opacity duration-300 ease-in-out absolute right-3 top-9" id="cross" :class="{'opacity-100': showCross, 'opacity-0': !showCross}"><i class="bx bx-dots-vertical-rounded text-2xl text-gray-600"></i></a>
      <a href="#" @click.prevent="openModal(data.id)" class="hover:scale-110 transition-opacity duration-300 ease-in-out absolute right-3 top-3" id="cross" :class="{'opacity-100': showCross, 'opacity-0': !showCross}"><i class=" bx bx-x text-2xl text-gray-600"></i></a>
    <p class="text-2xl font-bold pr-4">{{data.title}}</p>
    <div class="mt-2 ">
      <p class="">{{ data.content }}</p>
    </div>
  </div>
</template>

<style scoped>
#card{
    flex-grow: 1; /* Allow the card to expand and take available space */
    max-width: calc(33.333% - 1rem); /* Calculate the maximum width to ensure full width for each card */
    min-width: 200px;
}

@media only screen and (max-width: 1210px) {
    #card{
        max-width: 350px;
        min-width: 200px;
    }
}
@media only screen and (max-width: 870px) {
    #card{
        max-width: 100%;
        min-width: 100%;
        width: 100%;
    }
}

.custom-scroll {
  /* Your other styles */
}

/* Apply Tailwind CSS classes to customize scrollbar */
.custom-scroll::-webkit-scrollbar {
  width: 2px; /* Width of the scrollbar */
}

.custom-scroll::-webkit-scrollbar-track {
  //background-color: #f48a06; /* Color of the track behind the scrollbar */
}

.custom-scroll::-webkit-scrollbar-thumb {
  //background-color: #f48a06; /* Color of the scrollbar thumb */
  border-radius: 2px; /* Radius of the scrollbar thumb */
}
</style>
