import { defineStore } from "pinia";

export const useHomeLayoutStore = defineStore('home-layout',{
    state:()=>({
        isSideBarOpen:false
    }),
    actions:{
        toggleSideBar(){
            if(window.innerWidth < 1024)
                this.isSideBarOpen = !this.isSideBarOpen
        }
    }
})
