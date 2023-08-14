<template>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" />
    <div class="min-h-screen flex flex-row bg-gray-100">
        <transition name="slide-fade" mode="out-in" class="">
            <div v-if="menuOpen"  id="my_menu" class=" flex flex-col w-96 bg-gray-200 rounded-3xl m-2  overflow-y-auto items-center" key="menu">
                <div class="flex items-center h-20 shadow-md justify-between w-full">
                    <h1 class="ml-6 text-3xl uppercase text-indigo-500">Menu</h1>
                    <span class="cursor-pointer inline-flex items-center justify-center h-12 w-12 text-3xl text-gray-400" @click="toggleMenu"><i class="bx bx-menu"></i></span>
                </div>
                <ul class="flex flex-col py-4 w-full">
                    <li>
                        <Link href="/upcoming" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                            <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bxs-chevrons-right"></i></span>
                            <span  class="text-sm font-medium">Upcoming</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                            <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-list-ol"></i></span>
                            <span class="text-sm font-medium">Today</span>
                        </Link>
                    </li>
                    <li>
                        <a href="#" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                            <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bxs-sticker"></i></span>
                            <span class="text-sm font-medium">Sticky wall</span>
                        </a>
                    </li>
                </ul>
                    <hr class="border-b border-gray-300 w-5/6">
             <!--               LISTS                    -->
                <div class="w-full px-4 pt-4 mb-4">
                    <div class="mb-3">
                        <span class="">Lists</span>
                    </div>
                    <div v-if="lists" class="mb-3">
                        <Link :href="route('home', {category: list.category})" v-for="list in lists" class="flex justify-between items-center pl-2 mb-2 rounded cursor-pointer hover:bg-gray-300">
<!--                            <p :class="`${list.color} cursor-pointer rounded-lg px-2 py-0.2 mb-2`">{{ list.category }}</p>-->
                            <div class="flex items-center gap-3">
                                <div :class="`${list.color}  rounded w-4 h-4`"></div>
                                <p>{{list.category}}</p>
                            </div>
                            <div class="flex text-xs px-1 border border-gray-400 rounded mr-2">
                                <p class="">{{ list.tasks.length }}</p>
                            </div>
                        </Link>
                    </div>
                    <div class="mb-3">
                        <button @click.prevent="toggleList" class="flex items-center text-left border border-gray-300 text-gray-600 rounded-lg p-2 w-full text-sm hover:bg-gray-300 hover:text-gray-900"><i class="bx text-xl bx-plus mr-3"></i>Add new list</button>
                    </div>
                    <div v-if="newListOn">
                        <div class="border border-gray-300 p-3 rounded-lg">
                            <div class="flex border border-gray-300 items-center pl-4 rounded-lg mb-3">
                                <div id="listColor" :class="`${categoryColor} w-4 h-4 rounded`"></div>
                                <input type="hidden" v-model="categoryColor" name="list_color">
                                <input v-model="category" type="text" class="ml-3 bg-gray-200 border-none border-transparent focus:border-transparent focus:ring-0 h-1/3 text-sm w-3/4" placeholder="List">
                            </div>
                            <div v-if="this.errors.category">
                                <p class="text-red-500 text-xs text-center mb-4">{{this.errors.category}}</p>
                            </div>
                            <div class="flex flex-wrap gap-4 w-full mb-3">
                                <div @click="setListColor" class="bg-sky-600 cursor-pointer w-4 h-4 rounded"></div>
                                <div @click="setListColor" class="bg-purple-500 cursor-pointer w-4 h-4 rounded"></div>
                                <div @click="setListColor" class="bg-red-500 cursor-pointer w-4 h-4 rounded"></div>
                                <div @click="setListColor" class="bg-pink-500 cursor-pointer w-4 h-4 rounded"></div>
                                <div @click="setListColor" class="bg-lime-500 cursor-pointer w-4 h-4 rounded"></div>
                                <div @click="setListColor" class="bg-yellow-500 cursor-pointer w-4 h-4 rounded"></div>
                                <div @click="setListColor" class="bg-orange-500 cursor-pointer w-4 h-4 rounded"></div>
                            </div>
                            <div class="w-full text-right">
                                <button @click.prevent="categoryStore" class="rounded-lg text-sm bg-yellow-400 border border-gray-300 py-1 px-3 hover:bg-emerald-600 hover:text-white">Add list</button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="border-b border-gray-300 w-5/6">
                <!--               TAGS                    -->
                <div class="w-full px-4 pt-4 mb-8">
                    <div class="mb-3">
                        <span class="">Tags</span>
                    </div>
                    <div class="flex flex-wrap mb-3">
                        <div v-if="tags" class="flex flex-wrap gap-1 text-sm">
                            <Link :href="route('home', {tag: tag.tag})" v-for="tag in tags">
                                <p :class="`${tag.color} hover:border hover:border-gray-500 border cursor-pointer rounded-lg px-2 py-0.2`">{{tag.tag}}</p>
                            </Link>
                        </div>
                        <div class="p-1">
                            <button @click.prevent="toggleTag" class="flex items-center justify-center text-gray-600 text-sm border border-gray-300 rounded-lg px-1.5 py-1 hover:bg-gray-300 hover:text-gray-900"><i class="bx bx-plus"></i>Add Tag</button>
                        </div>
                    </div>
                    <div v-if="newTagOn" class="border border-gray-300 p-3 rounded-lg">
                        <div class="flex border border-gray-300 items-center pl-4 rounded-lg mb-3">
                            <div :class="`${tagColor} w-4 h-4 rounded-full`"></div>
                            <input v-model="tagColor" type="hidden" name="tag_color">
                            <input v-model="tagName" type="text" class="ml-3 bg-gray-200 border-none border-transparent focus:border-transparent focus:ring-0 h-1/3 text-sm w-3/4" placeholder="Tag">
                        </div>
                        <div class="flex flex-wrap gap-4 w-full mb-3 justify-center">
                            <div @click="setTagColor" class="bg-fuchsia-300 cursor-pointer w-4 h-4 rounded-full"></div>
                            <div @click="setTagColor" class="bg-indigo-300 cursor-pointer w-4 h-4 rounded-full"></div>
                            <div @click="setTagColor" class="bg-amber-300 cursor-pointer w-4 h-4 rounded-full"></div>
                            <div @click="setTagColor" class="bg-orange-300 cursor-pointer w-4 h-4 rounded-full"></div>
                        </div>
                        <div class="w-full text-right">
                            <button @click.prevent="tagStore" class="rounded-lg text-sm bg-indigo-400 text-gray-800 border border-gray-300 py-1 px-3 hover:bg-emerald-600 hover:text-white">Add tag</button>
                        </div>
                    </div>
                </div>
                <div class="ml-8 flex flex-col justify-center w-full h-full my-8 gap-2">
                    <div>
                        <button class="flex text-sm text-gray-800 justify-center items-center gap-1"><i class="bx bx-slider"></i>Settings</button>
                    </div>
                    <div>
                        <button @click.prevent="logout" class="flex text-sm text-gray-800 justify-center items-center gap-1"><i class="bx bx-log-out"></i>Sign out</button>
                    </div>
                </div>
            </div>
            <!--      BURGER MENU        -->
            <div v-else class="flex h-12 w-12 bg-gray-200 rounded-3xl m-2" key="icon">
                <div class="flex justify-between">
                    <span class="cursor-pointer inline-flex items-center justify-center h-12 w-12 text-3xl text-gray-400" @click="toggleMenu"><i class="bx bx-menu"></i></span>
                </div>
            </div>
        </transition>
            <slot />
        </div>
</template>

<script>
import {Link} from "@inertiajs/vue3";
// import Upcoming from "@/Pages/Upcoming.vue";

export default {
    name: "Main",
    props:[
        'tags',
        'lists'

    ],
    components:{
        // Upcoming,
        Link
    },
    data(){
        return{
            errors:'',
            menuOpen: true,
            newListOn: false,
            newTagOn: false,
            taskRedactor: false,
            category: '',
            categoryColor: 'bg-sky-600',
            tagName: '',
            tagColor: 'bg-fuchsia-300',
        }
    },
    methods: {
        logout(){
          this.$inertia.post('/logout')
        },
        toggleMenu() {
            this.menuOpen = !this.menuOpen;
        },
        toggleList(){
            this.newListOn = !this.newListOn
        },
        toggleTag(){
            this.newTagOn = !this.newTagOn
        },
        setListColor(e){
            this.categoryColor = e.srcElement.className.split(' ')[0]

        },
        categoryStore(){
            this.$inertia.post('/category', {category: this.category, color: this.categoryColor}, {
                onSuccess:(res)=>{
                    Object.assign(this.lists, res.props.lists)
                },
                onError:(error)=>{
                    this.errors = error
                }
            })
            this.category = ''
        },
        setTagColor(e){
            this.tagColor = e.srcElement.className.split(' ')[0]
        },
        tagStore(){
            this.$inertia.post('/tag', {tag: this.tagName, color: this.tagColor}, {
                onSuccess:(res)=>{
                    Object.assign(this.tags, res.props.tags)
                    this.tagName = '';
            }
            })
        }
    },
}
</script>
<style>
#my_menu {
    height: 100vh; /* Set the desired height for my_menu */
}
.slide-fade-enter-active {
    transition: 0.3s;
}

.slide-fade-leave-active {
    transition: .3s;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(-100%);
    opacity: 0;
}
</style>



