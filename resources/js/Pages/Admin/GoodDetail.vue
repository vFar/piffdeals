<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import AdminNavbar from "@/Components/AdminNavbar.vue";
import dayjs from "dayjs"; // Import Day.js
import { ref, reactive, onMounted, computed } from "vue";
import Modal from "@/Components/Modal.vue"; // Assuming this is your modal component
import PrimaryButton from "@/Components/PrimaryButton.vue"; // Assuming this is your modal component
import SecondaryButton from "@/Components/SecondaryButton.vue"; // Assuming this is your modal component
import InputLabel from "@/Components/InputLabel.vue"; // Assuming this is your modal component
import TextInput from "@/Components/TextInput.vue"; // Assuming this is your modal component
import { InputNumber, Tooltip, Image, Popconfirm } from "ant-design-vue";
import Editor from "@tinymce/tinymce-vue";
import { InboxOutlined } from "@ant-design/icons-vue";
import { message, Upload, Select } from "ant-design-vue";
import { router } from "@inertiajs/vue3";
import SelectInput from "@/Components/SelectInput.vue";
import DangerButton from "@/Components/DangerButton.vue";

const getImageUrl = (filename) => {
    if (filename && filename !== "/images/S-1.png") {
        return filename; // No need to add the prefix
    }
    return "";
};

const props = defineProps({
    good: Object,
    activeCategories: Array,
    activeGroups: Array,
    activeAttributes: Array,
});

const { errors } = usePage().props;
const form = useForm({
    id: props.good.id,
    name: props.good.name,
    description: props.good.description,
    image: props.good.image,
    price: props.good.price, // Convert to number
    stock_quantity: props.good.stock_quantity, // Convert to number
    status: props.good.status,
    group_id: props.good.group_id, // Set initial value
    attribute_id: props.good.attribute_id, // Set initial value
});

const updatePrice = (value) => {
    form.price = value;
};

const updateStockQuantity = (value) => {
    form.stock_quantity = value;
};

// Check if all form fields are filled
const canActivateProduct = computed(() => {
    return (
        form.name &&
        form.description &&
        form.stock_quantity &&
        form.image &&
        form.status
    );
});

// Handle form submission
const saveProduct = () => {
    form.patch(`/admin-goods/${form.id}`, {
        // Using 'put' for the update operation
        preserveScroll: true,
        onSuccess: () => message.success("Prece veiksmīgi atjaunināta!"),
        onError: () => message.error("Visi lauki nav aizpildīti!"),
    });
};

const handleChange = (value, key) => {
    form[key] = value || null; // Set the value to null if cleared
};

// File upload setup
const headers = {
    "X-CSRF-TOKEN": document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
};

const beforeUpload = (file) => {
    const isJpgPngWebp =
        file.type === "image/jpeg" ||
        file.type === "image/png" ||
        file.type === "image/webp";
    if (!isJpgPngWebp) {
        message.error(
            "Atļauts augšupielādēt tikai JPEG, PNG un WEBP attēla formātus!"
        );
    }
    const isLt2M = file.size / 1024 / 1024 < 2;
    if (!isLt2M) {
        message.error("Attēls nedrīkst būt lielāks par 2MB!");
    }
    return isJpgPngWebp && isLt2M;
};

const handleUploadChange = (info) => {
    if (info.file.status === "done") {
        const filename = info.file.response.url.split("/").pop();
        form.image = filename;
        message.success("Attēls veiksmīgi augšupielādēts");
    } else if (info.file.status === "error") {
        message.error(
            `Attēlu augšupielādēt neizdevusies: ${info.file.error.message}`
        );
    }
};

// const handleUploadChange = (info) => {
//     console.log("Upload event:", info);
//     if (info.file.status === "done") {
//         form.image = info.file.response.url;
//         message.success("Image uploaded successfully");
//         console.log("Image uploaded successfully:", info.file.response.url);
//     } else if (info.file.status === "error") {
//         message.error(`Image upload failed: ${info.file.error.message}`);
//         console.error("Image upload failed:", info.file.error.message);
//     }
// };

// const handleUploadChange = (info) => {
//     if (info.file.status === "done") {
//         // Extract only the filename from the response.url
//         const filename = info.file.response.url.split("/").pop();

//         // Update the form.image value
//         form.image = filename; // Store ONLY the filename

//         message.success("Attēls veiksmīgi augšupielādēts");
//     } else if (info.file.status === "error") {
//         message.error(`Image upload failed: ${info.file.error.message}`);
//         console.error("Image upload failed:", info.file.error.message);
//     }
// };

const statusOptions = reactive([
    { value: "Aktīvs", text: "Aktīvs" },
    { value: "Deaktivizēts", text: "Deaktivizēts" },
]);

onMounted(() => {
    // Reset form with current props when component mounts to refresh any data
    form.reset(props.good);
});

const token = ref(
    document.head
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content")
);

// Update category input based on the initial group on component mount
onMounted(() => {
    form.reset(props.good);
    if (props.good.group_id) {
        form.group_id = props.good.group_id; // Update form.group_id to match the initial group
    }
});

const confirmDeleteVisible = ref(false); // Track popconfirm visibility

const deleteGood = (id) => {
    router.delete(`/admin-goods/${id}`, {
        preserveScroll: true,
        onSuccess: () => {
            message.success("Prece veiksmīgi dzēsta!");
            // Optionally refresh the goods list here
        },
        onError: () => message.error("Neizdevās dzēst preci."),
    });
    confirmDeleteVisible.value = false; // Close the popconfirm
};
</script>

<style scoped>
/* Ensure the full offscreen translate amount is correct */
.drawer {
    transform: translateX(100%);
}
</style>

<template>
    <Head title="REDIĢĒT PRECI" />
    <div class="cross-patternSVGLight bg-whiter">
        <AdminNavbar currentPage="goods" />
        <main class="flex-1 ml-64">
            <section
                class="max-w-full mx-6 border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6 mb-8"
            >
                <div class="flex justify-between items-center py-2">
                    <div>
                        <h1
                            class="uppercase font-semibold text-xl text-textColor"
                        >
                            Rediģēt preci
                        </h1>
                    </div>

                    <div class="flex items-center space-x-6">
                        <Link
                            href="/admin-goods"
                            class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-textColor focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                        >
                            <button>
                                <i class="fa-solid fa-caret-left fa-fw"></i>
                                ATPAKAĻ
                            </button>
                        </Link>

                        <button
                            @click="saveProduct"
                            class="flex items-center p-3 rounded-lg text-white bg-accent uppercase font-semibold hover:bg-secondary group navbarElement"
                        >
                            SAGLABĀT preci
                        </button>
                    </div>
                </div>
            </section>

            <div class="flex">
                <section
                    class="w-3/4 mx-6 border border-gray-200 rounded-xl bg-whiter shadow-md py-3 mb-10"
                >
                    <div class="mx-auto max-w-full">
                        <div class="relative overflow-hidden">
                            <div
                                class="relative overflow-hidden flex justify-between mx-6 mb-10"
                            >
                                <h1
                                    class="uppercase font-semibold text-xl text-textColor"
                                >
                                    Preces informācija
                                </h1>
                            </div>

                            <form @submit.prevent="saveProduct">
                                <div class="overflow-x-auto space-y-6 mx-6">
                                    <div>
                                        <InputLabel
                                            for="number"
                                            value="Statuss"
                                            class="mb-1"
                                        />

                                        <SelectInput
                                            :options="statusOptions"
                                            placeholder="Izvēlies statusu"
                                            class="w-2/4"
                                            v-model="form.status"
                                        />
                                    </div>

                                    <div>
                                        <InputLabel
                                            for="number"
                                            value="Cena"
                                            class="mb-1"
                                        />
                                        <InputNumber
                                            :controls="false"
                                            :value="form.price"
                                            @update:value="updatePrice"
                                            addonBefore="€"
                                            size="large"
                                            class="w-2/4 border border-gray-300 rounded-xl shadow-sm bg-slate-100 text-xl"
                                        />
                                    </div>

                                    <div>
                                        <InputLabel
                                            for="number"
                                            value="Preču skaits noliktavā"
                                            class="mb-1"
                                        />
                                        <InputNumber
                                            :controls="false"
                                            size="large"
                                            class="w-2/4 border border-gray-300 rounded-xl shadow-sm"
                                            :value="form.stock_quantity"
                                            @update:value="updateStockQuantity"
                                        />
                                    </div>

                                    <div>
                                        <InputLabel
                                            for="text"
                                            value="Nosaukums"
                                            class="mb-1"
                                        />
                                        <TextInput
                                            class="w-2/4 border border-gray-300 rounded-md shadow-sm"
                                            v-model="form.name"
                                        />
                                    </div>

                                    <div>
                                        <InputLabel
                                            for="text"
                                            value="Apraksts"
                                            class="mb-1"
                                        />

                                        <div id="sample" class="w-full">
                                            <Editor
                                                api-key="qwewvh1gx06maulj4833kcb1pazkze8x298en2kbrfsk8ziy"
                                                v-model="form.description"
                                                :init="{
                                                    height: 500,
                                                    menubar: false,
                                                    language: 'lv', // Set Latvian as the language
                                                    language_url:
                                                        '/tinymce/langs/lv.js', // URL to the Latvian language file
                                                    plugins: [
                                                        'advlist autolink lists link image charmap print preview anchor',
                                                        'searchreplace visualblocks code fullscreen',
                                                        'insertdatetime media table paste code help wordcount',
                                                    ],
                                                    toolbar:
                                                        'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
                                                }"
                                            />
                                        </div>
                                    </div>

                                    <div class="w-full">
                                        <InputLabel
                                            for="image-upload"
                                            value="Attēls"
                                            class="mb-1"
                                        />
                                        <!-- Image preview -->
                                        <div class="w-full mb-4">
                                            <Image
                                                :src="form.image"
                                                fallback="/images/S-1.png"
                                                alt="Product Image"
                                                v-if="form.image"
                                                width="100px"
                                                height="100px"
                                                :preview="{ src: good.image }"
                                                previewMask="Priekšskatījums"
                                                class="cursor-pointer"
                                            />
                                        </div>
                                        <div class="w-full">
                                            <Upload
                                                class="upload-dragger"
                                                type="drag"
                                                name="image"
                                                action="/admin-goods/upload-image"
                                                :maxCount="1"
                                                accept="image/jpeg, image/png, image/webp"
                                                :headers="headers"
                                                @change="handleUploadChange"
                                                :beforeUpload="beforeUpload"
                                            >
                                                <div
                                                    class="flex flex-col justify-center items-center"
                                                >
                                                    <p
                                                        class="ant-upload-drag-icon"
                                                    >
                                                        <InboxOutlined />
                                                    </p>
                                                    <p class="ant-upload-text">
                                                        Klikšķini vai velc
                                                        attēla failu uz šo
                                                        lauku, lai
                                                        augšupielādētu
                                                    </p>
                                                    <p class="ant-upload-hint">
                                                        Atļauts augšupielādēt
                                                        <span
                                                            class="text-red-500"
                                                            >tikai 1
                                                            attēlu!</span
                                                        >. Attēlu formāti: jpeg,
                                                        png un webp.
                                                    </p>
                                                </div>
                                            </Upload>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>

                <div class="flex flex-col w-1/4 mx-6">
                    <section
                        class="w-full mb-6 border border-gray-200 rounded-xl bg-whiter shadow-md py-3 pb-10"
                    >
                        <div class="mx-auto max-w-full">
                            <div class="relative overflow-hidden">
                                <div
                                    class="relative overflow-hidden flex justify-between mx-6 mb-10"
                                >
                                    <h1
                                        class="uppercase font-semibold text-xl text-textColor"
                                    >
                                        Preces kārtošana kategorijā
                                    </h1>
                                </div>
                                <div class="overflow-x-auto space-y-6 mx-6">
                                    <div>
                                        <InputLabel
                                            for="attribute"
                                            value="Atribūts"
                                            class="mb-1"
                                        />
                                        <Select
                                            class="w-full"
                                            v-model="form.attribute_id"
                                            :options="
                                                activeAttributes.map(
                                                    (attribute) => ({
                                                        value: attribute.id,
                                                        label: attribute.name,
                                                    })
                                                )
                                            "
                                            allowClear
                                            @change="
                                                (value) =>
                                                    handleChange(
                                                        value,
                                                        'attribute_id'
                                                    )
                                            "
                                            :defaultValue="
                                                props.good.attribute_id
                                            "
                                        />
                                    </div>
                                    <div>
                                        <InputLabel
                                            for="group"
                                            value="Grupa"
                                            class="mb-1"
                                        />
                                        <Select
                                            class="w-full"
                                            v-model="form.group_id"
                                            :options="
                                                activeGroups.map((group) => ({
                                                    value: group.id,
                                                    label: group.name,
                                                }))
                                            "
                                            @change="
                                                (value) =>
                                                    handleChange(
                                                        value,
                                                        'group_id'
                                                    )
                                            "
                                            allowClear
                                            :defaultValue="props.good.group_id"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <Popconfirm
                        v-model:open="confirmDeleteVisible"
                        title="Vai tiešām vēlies dzēst preci? Šī darbība ir neatgriezeniska."
                        @confirm="deleteGood(good.id)"
                        ok-text="Dzēst"
                        cancel-text="Atcelt"
                    >
                        <DangerButton
                            class="uppercase flex justify-center shadow-md"
                            :class="{ 'opacity-25': form.processing }"
                        >
                            <span class="text-md">Dzēst preci</span>
                        </DangerButton>
                    </Popconfirm>
                </div>
            </div>
        </main>
    </div>
</template>
