<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed, onMounted, watch } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import { Ziggy } from "@/ziggy";
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import AdminNavbar from "@/Components/AdminNavbar.vue";
import AdminPaginator from "@/Components/AdminPaginator.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import dayjs from "dayjs"; // Import Day.js
import { router } from "@inertiajs/vue3";
import AdminFilterDrawer from "@/Components/AdminFilterDrawer.vue";
import AdminSearchbar from "@/Components/AdminSearchbar.vue";
import { message, Image, Tooltip, Select, Popconfirm } from "ant-design-vue"; // Import Ant Design's message component

const props = defineProps({
    orders: Object,
    order: Object,
    totalOrders: Number,
});

function fetchOrders() {
    router.get(
        route("admin-orders.index"),
        { search: search.value },
        {
            preserveState: true,
            replace: true,
        }
    );
}

const changeStatusModal = ref(false);
const selectedOrder = ref({});
const statusOptions = ["Gaida", "Procesā", "Atcelts", "Pabeigts"];

const orderStatusForm = useForm({
    status: "",
});

const openChangeStatusModal = (order) => {
    if (order.status !== "Atcelts") {
        selectedOrder.value = order;
        orderStatusForm.status = order.status; // Directly set the status
        changeStatusModal.value = true;
    }
};

const closeChangeStatusModal = () => {
    changeStatusModal.value = false;
};

const updateOrderStatus = async () => {
    if (orderStatusForm.status === "Atcelts") {
        // Optionally handle stock quantity reversal here
    }
    // Continue with updating the order status
    await router.patch(
        route("admin-orders.update", { id: selectedOrder.value.id }),
        {
            status: orderStatusForm.status,
        }
    );
    changeStatusModal.value = false;
    fetchOrders(); // Refresh orders list
    message.success("Pasūtījuma statuss veiksmīgi atjaunināts!");
};

const getStatusClass = (status) => {
    switch (status) {
        case "Gaida":
            return "text-amber-500 bg-orange-100";
        case "Atcelts":
            return "text-red-600 bg-red-100";
        case "Procesā":
            return "text-blue-400 bg-blue-100";
        case "Pabeigts":
            return "text-green-400 bg-green-100";
        default:
            return "text-gray-500 bg-gray-100"; // Default styling for other statuses
    }
};

const formatDate = (date) => {
    return dayjs(date).format("DD.MM.YYYY, HH:mm:ss");
};

function handlePageChange(url) {
    router.replace(url);
}
</script>

<style scoped>
/* Ensure the full offscreen translate amount is correct */
.drawer {
    transform: translateX(100%);
}
</style>

<template>
    <Head title="PASŪTĪJUMI" />
    <div class="cross-patternSVGLight bg-whiter">
        <AdminNavbar currentPage="orders" />

        <main class="flex-1 ml-64">
            <section
                class="max-w-full mx-6 border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6 mb-8"
            >
                <div class="flex justify-between items-center py-2">
                    <h1 class="uppercase font-semibold text-xl text-textColor">
                        Klientu pasūtījumi
                    </h1>
                </div>
            </section>

            <section
                class="max-w-full mx-6 border border-gray-200 rounded-xl bg-whiter shadow-md py-3"
            >
                <div class="mx-auto max-w-full">
                    <!-- Start coding here -->
                    <div class="relative overflow-hidden">
                        <div
                            class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 mx-4 p-4"
                        >
                            <div class="relative overflow-hidden">
                                <div class="flex space-x-3 items-center">
                                    <p>
                                        {{ totalOrders }}
                                        {{
                                            totalOrders === 1
                                                ? "rezultāts"
                                                : "rezultāti"
                                        }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0"
                            >
                                <div
                                    class="flex items-center space-x-6 w-full md:w-auto"
                                >
                                    <div class="flex items-center space-x-6">
                                        <AdminPaginator :links="orders.links" @page-change="handlePageChange" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table
                                class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                            >
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                                >
                                    <tr>
                                        <th scope="col" class="px-4 py-3">
                                            ID / Datums / Statuss
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Kopsumma €
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Klients / E-pasts / Telefons /
                                            Adrese
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Attēls / Skaits
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            <span class="sr-only"
                                                >Darbības</span
                                            >
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="order in orders.data"
                                        :key="order.id"
                                    >
                                        <td class="px-4 py-3 space-y-3">
                                            <span class="font-semibold"
                                                >#{{ order.id }}</span
                                            ><br />
                                            {{ formatDate(order.created_at)
                                            }}<br />
                                            <span
                                                :class="
                                                    getStatusClass(order.status)
                                                "
                                                class="px-2 py-1 rounded-lg inline-block"
                                            >
                                                {{ order.status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ order.total }} €
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ order.user.name }} <br />
                                            {{ order.user.email }} <br />
                                            {{ order.address.street }},
                                            {{ order.address.city }} <br />
                                            {{ order.address.phone_number }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-wrap gap-2">
                                                <div
                                                    v-for="item in order.items"
                                                    :key="item.id"
                                                    class="relative"
                                                >
                                                    <Tooltip
                                                        placement="top"
                                                        :title="item.good.name"
                                                    >
                                                        <div
                                                            class="relative cursor-pointer"
                                                        >
                                                            <Image
                                                                :src="
                                                                    item.good
                                                                        .image
                                                                "
                                                                class="rounded"
                                                                width="60px"
                                                                height="60px"
                                                                fallback="/images/S-1.png"
                                                                previewMask="Priekšskatījums"
                                                            />
                                                            <span
                                                                class="absolute top-0 right-0 bg-red-600 text-white text-xs rounded-full px-2"
                                                                style="
                                                                    pointer-events: none;
                                                                "
                                                            >
                                                                {{
                                                                    item.quantity
                                                                }}
                                                            </span>
                                                        </div>
                                                    </Tooltip>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right px-4 py-3">
                                            <button @click="openChangeStatusModal(order)" :class="[order.status === 'Atcelts' ? 'hover:bg-slate-200 cursor-not-allowed' : 'hover:bg-slate-200', 'text-xl rounded-lg p-2']" :disabled="order.status === 'Atcelts'">
                                                <i class="fa-solid fa-pen-to-square fa-fw text-textColor"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <Modal
                                :show="changeStatusModal"
                                @close="closeChangeStatusModal"
                            >
                                <form @submit.prevent="updateOrderStatus">
                                    <div class="pt-6">
                                        <div
                                            class="flex items-center justify-between border-b mb-4"
                                        >
                                            <h3 class="text-xl p-4 uppercase">
                                                Atjaunināt pasūtījuma statusu
                                            </h3>
                                            <button
                                                @click="closeChangeStatusModal"
                                                type="button"
                                                class="m-4 bg-transparent hover:bg-gray-300 text-white p-2 rounded-xl"
                                            >
                                                <i
                                                    class="fas fa-xmark fa-fw text-textColor hover:text-white"
                                                ></i>
                                            </button>
                                        </div>
                                        <div class="mb-16 px-6">
                                            <Select
                                                v-model:value="
                                                    orderStatusForm.status
                                                "
                                                style="width: 100%"
                                            >
                                                <Select.Option
                                                    v-for="status in statusOptions"
                                                    :key="status"
                                                    :value="status"
                                                >
                                                    {{ status }}
                                                </Select.Option>
                                            </Select>
                                        </div>
                                    </div>
                                    <div
                                        class="mx-6 py-6 border-t flex justify-end"
                                    >
                                        <SecondaryButton
                                            @click="closeChangeStatusModal"
                                            >Atcelt</SecondaryButton
                                        >
                                        <!-- Conditional Popconfirm for status "Atcelts" -->
                                        <Popconfirm
                                            title="Mainot statusu uz 'Atcelts' ir neatgriezenisks process, noliktavas preču skaits tiks atjaunots. Turpināt?"
                                            v-if="
                                                orderStatusForm.status ===
                                                'Atcelts'
                                            "
                                            @confirm="updateOrderStatus"
                                            okText="Jā"
                                            cancelText="Nē"
                                        >
                                            <PrimaryButton type="button"
                                                >Saglabāt</PrimaryButton
                                            >
                                        </Popconfirm>
                                        <!-- Default Save button when not "Atcelts" -->
                                        <PrimaryButton
                                            v-else
                                            @click="updateOrderStatus"
                                            type="button"
                                        >
                                            Saglabāt
                                        </PrimaryButton>
                                    </div>
                                </form>
                            </Modal>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>
