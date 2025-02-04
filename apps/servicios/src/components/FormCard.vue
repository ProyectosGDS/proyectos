<script setup>

</script>

<template>
    <Card class=" px-2 py-5 md:p-5 bg-white ">
        <div class="grid xl:grid-cols-2 xl:divide-x">
            <div>
                <slot name="left"></slot>    
            </div>
            <div>
                <slot name="right"></slot>
            </div>
        </div>
        <hr v-if="$slots.footer" class="my-4">
        <slot name="footer"></slot>
    </Card>
</template>