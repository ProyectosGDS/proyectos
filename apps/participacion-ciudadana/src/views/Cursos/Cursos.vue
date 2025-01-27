<script setup>
    import { onMounted } from 'vue'
    import { useCursosStore } from '@/stores/cursos'
import LoadingBar from '@/components/LoadingBar.vue';

    const store = useCursosStore()

    onMounted(() => {
        store.fetch()
    })
</script>

<template>
    <div class="p-8">
        <Data-Table :headers="store.headers" :data="store.cursos" :export="false" :filterAdvance="false">
            <template #tbody="{items}">
                <tr v-for="item in items" @click="store.detalleCurso(item)" title="Click para mas detalles">
                    <td>{{ item.id_clase }}</td>
                    <td>{{ item.curso }}</td>
                    <td>{{ item.nivel }}</td>
                    <td>{{ item.sede }}</td>
                    <td>{{ item.temporalidad }}</td>
                    <td>{{ (item.modalidad == 'P') ? 'PRESENCIAL' : (item.modalidad == 'V' ? 'VIRTUAL' : 'HIBRIDO') }}</td>
                </tr>
            </template>
        </Data-Table>    
        <LoadingBar v-if="store.loading" class="h-1 bg-violet-400" />
    </div>
</template>

<style scoped>
    td {
        @apply py-3 text-gray-800;
    }

    tr {
        @apply cursor-pointer hover:bg-violet-100;
    }
</style>