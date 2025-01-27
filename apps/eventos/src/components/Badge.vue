<script setup>
    const props = defineProps({
        text : {
            type : String,
            default : ''
        },
        item : {
            type : Object,
            default : () => {}
        }
    })
</script>

<template>
    <div class="text-xs px-1.5 py-0.5 rounded-full text-nowrap text-center" 
            :class="{
                'bg-green-200 text-green-500' : item.id_estatus == 1,
                'bg-orange-200 text-orange-500' : item.id_estatus == 2, 
                'bg-red-200 text-red-500' : item.id_estatus == 3, 
            }">
    
        {{ props.text }}
    </div>
</template>
