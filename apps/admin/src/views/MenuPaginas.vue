<script setup>
    import { useAuthStore } from '@/stores/auth'
    import { onMounted } from 'vue'
    import { useMenuPaginasStore } from '@/stores/menu-paginas'

    const auth = useAuthStore()
    const store = useMenuPaginasStore()

    onMounted(() => store.fetch())
</script>

<template>
    
</template>