<script setup>
    import SideBar from '@/components/SideBar.vue'
    import UserCard from '@/components/UserCard.vue'
</script>

<template>
    <main class="h-screen flex">
        <SideBar />
        <section class="flex-1">
            <article class="h-full flex flex-col">
                <UserCard class="absolute top-2 right-7"/>
                <div class="overflow-auto flex-1 flex flex-col">
                    <div class="flex-1 pt-20 p-3">
                        <RouterView />
                    </div>
                </div>
            </article>
        </section>
    </main>
</template>
