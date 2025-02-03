<script setup>
    import { onClickOutside } from '@vueuse/core'
    import { onBeforeMount, onMounted, ref, watchEffect } from 'vue';

    const props = defineProps({
        items : {
            type : Array,
            default : () => []
        },

        modelValue : null
    })

    const emits = defineEmits(['update:modelValue'])
    
    const target = ref(null)
    
    const selectedItems = ref([])
    
    const open = ref(false)
    const text = ref('-- Seleccione --')
    
    onClickOutside(target, (event) => open.value = false)
    
    onBeforeMount(() => {
        selectedItems.value = props.modelValue
    })
    
    watchEffect(() => {
        if(selectedItems.value.length > 0 && selectedItems.value.length < 2) {
            text.value = selectedItems.value.length + ' enfermedad seleccionada'
        }else if(selectedItems.value.length > 1) {
            text.value = selectedItems.value.length + ' enfermedades seleccionadas'
        } else {
            text.value = '-- Seleccione --'
        }
        emits('update:modelValue',selectedItems.value)
    })

</script>

<template>
    <div class="relative" ref="target">
        <div @click="open = !open"  class="input flex items-center uppercase cursor-pointer select-none">{{ text }}</div>
        <Transition name="fade">
            <div v-show="open" class="absolute bg-white p-4 text-xs rounded-md border shadow-xl grid  z-20 h-72 overflow-auto">
                <label v-for="item in props.items" class="cursor-pointer hover:bg-gray-200 rounded-lg flex space-x-2 p-1">
                    <input v-model="selectedItems" type="checkbox" name="enfermedad" :value="item.id_enfermedad" class="select-none">
                    <span>{{ item.descripcion }}</span>
                </label>
            </div>
        </Transition>
    </div>
</template>
<style scoped>
    .fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s ease;
    }

    .fade-enter-from, .fade-leave-to {
    opacity: 0;
    }
</style>