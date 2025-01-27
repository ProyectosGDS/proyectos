<script setup>
    import { onMounted, ref } from 'vue'

    const props = defineProps({
        modalValue : null,
        error : false,
        preview : '',
        title : {
            type : String,
            default : 'Seleccione imagen'
        }
    })

    const emits =  defineEmits(['update:modelValue'])

    const image = ref(null)
    const preview = ref(null)

    const onFileChange = (event) => {
        const file = event.target.files[0];
        if (file && file.type.startsWith('image/')) {
            image.value = file;
            preview.value = URL.createObjectURL(file);
            emits('update:modelValue',file)
        } else {
            removeImage();
        }
    }

    const removeImage = () => {
        image.value = null;
        preview.value = null;
    }

    onMounted(() => {
        if(props.preview != '') {
            preview.value = props.preview
        }
    })

</script>

<template>
    <div class="image-upload flex flex-col items-center gap-4">
        <label class="flex items-center gap-3 cursor-pointer text-color-4 hover:scale-110" :class="{'text-red-500' : props.error}">
            <Icon icon="fas fa-cloud-arrow-up" class="text-4xl" />
            <p class="text-sm">{{ props.title }}</p>
            <input type="file" accept="image/*" @change="onFileChange" hidden />
        </label>
        <div v-if="preview" class="relative">
            <img :src="preview" alt="Image Preview" class="relative max-w-full h-auto rounded-md" />
            <Icon @click="removeImage" icon="fas fa-xmark" class="text-red-200 bg-red-500 cursor-pointer h-5 w-5 p-1 rounded-full absolute -top-1 -right-1" />
        </div>
    </div>
</template>
<!-- 
<script>
import { ref } from 'vue';

export default {
    name: 'ImageUpload',
    setup() {
        const image = ref(null); // Almacena el archivo seleccionado
        const preview = ref(null); // Almacena la URL para previsualizar la imagen

        const onFileChange = (event) => {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                image.value = file;
                preview.value = URL.createObjectURL(file);
            } else {
                alert('Por favor selecciona un archivo de imagen.');
                reset();
            }
        };

        const removeImage = () => {
            reset();
        };

        const uploadImage = async () => {
            if (!image.value) return;

            const formData = new FormData();
            formData.append('image', image.value);

            try {
                const response = await fetch('https://api.example.com/upload', {
                    method: 'POST',
                    body: formData,
                });

                if (response.ok) {
                    alert('Imagen subida exitosamente.');
                    reset();
                } else {
                    alert('Error al subir la imagen.');
                }
            } catch (error) {
                alert('Ocurrió un error al intentar subir la imagen.');
                console.error(error);
            }
        };

        const reset = () => {
            image.value = null;
            preview.value = null;
        };

        return {
            image,
            preview,
            onFileChange,
            removeImage,
            uploadImage,
        };
    },
};
</script> -->
