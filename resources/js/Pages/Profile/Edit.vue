<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head } from "@inertiajs/vue3";
import Footer from "@/Components/Footer.vue";
import Navbar from "@/Components/Navbar.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import Modal from "@/Components/Modal.vue";
import { computed, ref } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import { Button } from "ant-design-vue";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import "dayjs/locale/lv"; // Import Latvian locale

dayjs.extend(relativeTime);
dayjs.locale("lv"); // Use Latvian locale

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const page = usePage();

// const pageProps = usePage().props;
// const authUser = computed(() => pageProps.auth.user);

// const isAuthenticated = computed(() => !!authUser.value);
// const isAdmin = computed(() => authUser.value?.role_id === 2);

const { auth } = usePage().props;

const isAdmin = computed(() => auth.user && auth.user.role_id === 2);

const formatRelativeDate = (date) => {
    return dayjs(date).fromNow();
};

const formatDate = (date) => {
    return dayjs(date).format("DD.MM.YYYY");
};

const userFirstLetter = computed(() => auth.user.name.charAt(0).toUpperCase());

const showEditProfileModal = ref(false);
const showEditPasswordModal = ref(false);

const openEditProfileModal = () => {
    showEditProfileModal.value = true;
};

const closeEditProfileModal = () => {
    showEditProfileModal.value = false;
};

const openEditPasswordModal = () => {
    showEditPasswordModal.value = true;
};

const closeEditPasswordModal = () => {
    showEditPasswordModal.value = false;
};
</script>
<template>
    <Head title="PROFILS" />

    <Navbar />
    <AuthenticatedLayout>
        <div class="py-8">
            <Breadcrumbs currentPage="Profils" />
        </div>
        <!-- border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6 -->
        <div
            class="flex gap-10 items-start mt-8 max-lg:gap-5 max-md:grid container max-w-screen-2xl mb-24"
        >
            <div
                class="panel-side grid grid-cols-[1fr] gap-8 max-w-[380px] max-h-[1320px] overflow-y-auto overflow-x-hidden journal-scroll min-w-[342px] max-xl:min-w-[270px] max-md:max-w-full"
            >
                <div
                    class="grid grid-cols-[auto_1fr] gap-4 items-center px-5 py-[25px] border border-gray-200 rounded-xl bg-whiter shadow-md"
                >
                    <div
                        class="flex items-center justify-center w-[60px] h-[60px] bg-gray-100 text-primary text-2xl font-bold rounded-full"
                    >
                        {{ userFirstLetter }}
                    </div>

                    <div class="flex flex-col justify-between">
                        <p class="text-2xl font-medium">
                            Sveiki, {{ $page.props.auth.user.name }}!
                        </p>
                        <p class="text-textColor">
                            {{ $page.props.auth.user.email }}
                        </p>
                    </div>
                </div>
                <div
                    class="max-md:border max-md:border-t-0 max-md:border-r max-md:border-l-[1px] max-md:border-b max-md:border-gray-200 max-md:rounded-md"
                >
                    <div class="">
                        <div
                            class="bg-whiter rounded-t-xl border border-gray-200 max-md:flex max-md:gap-x-4 max-md:justify-between max-md:items-center py-4 px-8 max-md:bg-gray-100 max-md:px-6 max-md:py-5 max-md:rounded-tl-[6px] max-md:rounded-tr-[6px] accordian-toggle md:pointer-events-none select-none"
                        >
                            <p class="text-xl md:font-medium">Mans konts</p>
                            <span
                                class="icon-arrow-right text-2xl md:hidden"
                            ></span>
                        </div>
                        <div
                            class="bg-whiter shadow-md grid border border-t-0 border-r border-l-[1px] border-b border-gray-200 rounded-b-xl max-md:hidden max-md:border-none accordian-content"
                        >
                            <Link href="/profile">
                                <div
                                    class="flex justify-between px-6 py-5 border-t border-gray-200 hover:bg-[#f3f4f682] cursor-pointer"
                                >
                                    <p
                                        class="flex gap-x-4 items-center text-lg font-medium"
                                    >
                                        <span
                                            class="icon-users text-2xl"
                                        ></span>
                                        Profils
                                    </p>
                                    <span
                                        class="icon-arrow-right text-2xl max-md:hidden"
                                    ></span>
                                </div>
                            </Link>

                            <div v-if="!isAdmin">
                                <Link href="/profile/orders">
                                    <div
                                        class="flex justify-between px-6 py-5 border-t border-gray-200 hover:bg-[#f3f4f682] cursor-pointer"
                                    >
                                        <p
                                            class="flex gap-x-4 items-center text-lg font-medium"
                                        >
                                            <span
                                                class="icon-orders text-2xl"
                                            ></span>
                                            Pasūtījumu vēsture
                                        </p>
                                        <span
                                            class="icon-arrow-right text-2xl max-md:hidden"
                                        ></span>
                                    </div>
                                </Link>
                                <Link href="/cart">
                                    <div
                                        class="flex justify-between px-6 py-5 border-t border-gray-200 hover:bg-[#f3f4f682] cursor-pointer"
                                    >
                                        <p
                                            class="flex gap-x-4 items-center text-lg font-medium"
                                        >
                                            <span
                                                class="icon-star text-2xl"
                                            ></span>
                                            Iepirkumu grozs
                                        </p>
                                        <span
                                            class="icon-arrow-right text-2xl max-md:hidden"
                                        ></span>
                                    </div>
                                </Link>
                            </div>
                            <Link href="/admin-orders" v-if="isAdmin">
                                <div
                                    class="flex justify-between px-6 py-5 border-t border-gray-200 hover:bg-[#f3f4f682] cursor-pointer"
                                >
                                    <p
                                        class="flex gap-x-4 items-center text-lg font-medium"
                                    >
                                        <span class="icon-star text-2xl"></span>
                                        Administrācijas panelis
                                    </p>
                                    <span
                                        class="icon-arrow-right text-2xl max-md:hidden"
                                    ></span>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="flex-auto bg-whiter border border-gray-200 shadow-md rounded-xl"
            >
                <div class="flex justify-between mr-6 items-center py-6">
                    <h2 class="text-2xl font-medium pl-8 text-textColor">
                        Profils
                    </h2>
                    <Button.Group>
                        <Button @click="openEditProfileModal">
                            <i class="fas fa-user-pen fa-fw text-textColor"></i>
                        </Button>
                        <Button @click="openEditPasswordModal">
                            <i class="fas fa-lock fa-fw text-textColor"></i>
                        </Button>
                    </Button.Group>
                </div>
                <div class="grid grid-cols-1 gap-y-6 mt-8">
                    <div
                        class="grid grid-cols-[2fr_3fr] w-full px-8 py-3 border-b border-[#E9E9E9]"
                    >
                        <p class="text-sm font-medium">Vārds, uzvārds</p>
                        <p class="text-sm text-[#6E6E6E] font-medium">
                            {{ $page.props.auth.user.name }}
                        </p>
                    </div>
                    <div
                        class="grid grid-cols-[2fr_3fr] w-full px-8 py-3 border-b border-[#E9E9E9]"
                    >
                        <p class="text-sm font-medium">E-pasts</p>
                        <p class="text-sm font-medium text-[#6E6E6E]">
                            {{ $page.props.auth.user.email }}
                        </p>
                    </div>
                    <div
                        class="grid grid-cols-[2fr_3fr] w-full px-8 py-3 border-b border-[#E9E9E9]"
                    >
                        <p class="text-sm font-medium">Izveidots</p>
                        <p class="text-sm text-[#6E6E6E] font-medium">
                            {{
                                formatRelativeDate(
                                    $page.props.auth.user.created_at
                                )
                            }}
                        </p>
                    </div>

                    <DeleteUserForm class="py-6 px-8" />
                </div>
            </div>
        </div>
        <Footer />
    </AuthenticatedLayout>

    <!-- Edit Profile Modal -->
    <Modal :show="showEditProfileModal" @close="closeEditProfileModal">
        <form
            class="p-6 bg-white rounded-lg shadow overflow-hidden"
            @submit.prevent="
                form.patch(route('profile.update'), {
                    onSuccess: () => {
                        closeEditProfileModal();
                        message.success(
                            'Profila informācija atjaunināta veiksmīgi'
                        );
                    },
                })
            "
        >
            <div
                class="flex justify-between items-center border-b border-gray-200 mb-4 pb-4"
            >
                <h3 class="text-xl font-semibold text-gray-800 uppercase">
                    Rediģēt profilu
                </h3>
                <button
                    @click="closeEditProfileModal"
                    type="button"
                    class="m-4 bg-transparent hover:bg-gray-300 text-white p-2 rounded-xl"
                >
                    <i
                        class="fas fa-xmark fa-fw text-textColor hover:text-white"
                    ></i>
                </button>
            </div>
            <UpdateProfileInformationForm
                :must-verify-email="mustVerifyEmail"
                :status="status"
                class="max-w-full"
            />
        </form>
    </Modal>

    <!-- Edit Password Modal -->
    <Modal :show="showEditPasswordModal" @close="closeEditPasswordModal">
        <form
            class="p-6 bg-white rounded-lg shadow overflow-hidden"
            @submit.prevent="
                form.put(route('password.update'), {
                    onSuccess: () => {
                        closeEditPasswordModal();
                        message.success('Parole atjaunināta veiksmīgi');
                    },
                })
            "
        >
            <div
                class="flex justify-between items-center border-b border-gray-200 mb-4 pb-4"
            >
                <h3 class="text-xl font-semibold text-gray-800 uppercase">
                    Atjaunināt paroli
                </h3>
                <button
                    @click="closeEditPasswordModal"
                    type="button"
                    class="m-4 bg-transparent hover:bg-gray-300 text-white p-2 rounded-xl"
                >
                    <i
                        class="fas fa-xmark fa-fw text-textColor hover:text-white"
                    ></i>
                </button>
            </div>
            <UpdatePasswordForm class="max-w-full" />
        </form>
    </Modal>
</template>
