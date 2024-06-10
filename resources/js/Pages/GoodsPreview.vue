<script setup>
import { ref, computed, watch } from "vue";
import { Head, Link, usePage, router, useForm } from "@inertiajs/vue3";
import Navbar from "../Components/Navbar.vue";
import Footer from "../Components/Footer.vue";
import Breadcrumbs from "../Components/Breadcrumbs.vue";
import ScrollTopBtn from "../Components/ScrollToTopBtn.vue";
import {
    Carousel,
    Image,
    Button,
    Card,
    InputNumber,
    Drawer,
    notification,
} from "ant-design-vue";
import { h } from "vue"; // Ensure h is imported correctly

const { props } = usePage();
const { good } = props;

const auth = computed(() => props.auth.user);
const isAuthenticated = computed(() => !!auth.value);
const isEmailVerified = computed(
    () => !!auth.value && !!auth.value.email_verified_at
);

// Add to Cart Function
// Vue Component

const form = useForm({
    goodId: good.id,
    quantity: 1,
});

const updateQuantity = (value) => {
    form.price = value;
};

const addToCart = () => {
    if (!isAuthenticated.value) {
        router.visit("/login");
    } else if (!isEmailVerified.value) {
        router.visit("/verify-email");
    } else {
        form.post(route("cart.add"), {
            onSuccess: () => {
                notification.success({
                    message: "Prece veiksmīgi pievienota grozam!",
                    description: "Apskati grozi vai turpini iepirkšanos!",
                    btn: () =>
                        h(
                            "div",
                            { class: "space-x-6" },
                            [
                                h(
                                    Button,
                                    {
                                        type: "primary",
                                        onClick: () => {
                                            router.visit("/cart");
                                            notification.close();
                                        },
                                    },
                                    "Skatīt grozu"
                                ),
                            ]
                        ),
                    duration: 2, // Keep open until manually closed or an action is taken
                });
            },
            onError: () => {
                notification.error({
                    message: "Kļūda",
                    description:
                        "Neizdevās pievienot preci grozam. Lūdzu, mēģiniet vēlreiz.",
                });
            },
        });
    }
};
</script>

<style></style>

<template>
    <Head :title="props.currentPage" />

    <!-- <div>
        <h1>{{ good.name }}</h1>
        <p>SKU: {{ good.sku }}</p>
    </div> -->

    <Navbar />
    <ScrollTopBtn />
    <div class="cross-patternSVGLight bg-whiter">
        <div class="py-8">
            <Breadcrumbs :currentPage="props.currentPage" />
        </div>

        <div class="space-y-12">
            <div
                class="container max-w-screen-2xl border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6"
            >
                <div
                    class="flex flex-col md:flex-row items-center md:items-start gap-6"
                >
                    <div class="md:w-1/2 flex justify-center">
                        <Image
                            :src="good.image"
                            alt="goods-image"
                            class="object-cover rounded-lg select-none cursor-pointer hover:scale-110 mt-6 select-none"
                            fallback="/images/S-1.png"
                            :width="full"
                            :height="400"
                            :preview="{ src: good.image }"
                            previewMask="Priekšskatījums"
                        />
                    </div>
                    <div class="md:w-1/2">
                        <h1
                            class="text-textColor font-bold text-3xl mb-4 text-left"
                        >
                            {{ good.name }}
                        </h1>

                        <div class="flex justify-between items-center mb-12">
                            <p
                                class="text-textColor text-base font-semibold p-2 rounded-lg"
                                :class="{
                                    'text-green-500 bg-green-100':
                                        good.status === 'Aktīvs',
                                    'text-red-500 bg-red-100':
                                        good.status === 'Deaktivizēts',
                                }"
                            >
                                {{
                                    good.status === "Aktīvs"
                                        ? "Pieejams"
                                        : "Nav pieejams"
                                }}
                            </p>
                            <div class="flex space-x-6 items-center justify">
                                <p class="text-textColor text-sm font-semibold">
                                    ID:
                                    {{ good.id }}
                                </p>

                                <p class="text-textColor text-sm font-semibold">
                                    SKU:
                                    {{ good.sku }}
                                </p>
                            </div>
                        </div>

                        <p class="text-textColor text-2xl font-semibold">
                            {{ good.price }} €
                        </p>

                        <div class="flex items-center">
                            <InputNumber
                                v-model:value="form.quantity"
                                @update:value="updateQuantity"
                                min="1"
                            />
                            <span class="ml-2">gab</span>
                        </div>

                        <template
                            v-if="
                                ($page.props.auth.user &&
                                    $page.props.auth.user.role_id === 1) ||
                                !$page.props.auth.user
                            "
                        >
                            <div class="flex justify-center mt-6">
                                <button
                                    @click="addToCart"
                                    class="text-primary hover:before:bg-primary border-primary relative h-[50px] w-11/12 overflow-hidden border border-primary bg-white px-3 text-primary shadow-2xl transition-all before:absolute before:bottom-0 before:left-0 before:top-0 before:z-0 before:h-full before:w-0 before:bg-primary before:transition-all before:duration-500 hover:text-white hover:shadow-primary hover:before:left-0 hover:before:w-full"
                                >
                                    <span class="relative z-10 uppercase"
                                        >Pievienot grozam</span
                                    >
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
                <h1 class="uppercase mt-12 mb-6">Apraksts</h1>
                <div
                    v-html="good.description"
                    class="text-textColor text-lg mb-4"
                ></div>
            </div>

            <div
                class="container max-w-screen-2xl border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6"
            >
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
                    <div class="flex flex-col items-center py-6">
                        <div
                            class="bg-primary text-white rounded-full p-4 mb-2"
                        >
                            <i class="fas fa-headphones text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold pb-4">
                            Tehniskais atbalsts
                        </h3>
                        <p class="text-gray-600 text-center">
                            Piedāvājam tehnisko atbalstu, lai nodrošinātu jums
                            nevainojamu pieredzi.
                        </p>
                    </div>
                    <div
                        class="flex flex-col items-center border-x border-gray-300 py-6"
                    >
                        <div
                            class="bg-primary text-white rounded-full p-4 mb-2"
                        >
                            <i class="fas fa-store text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold pb-4">
                            Plašs preču klāsts
                        </h3>
                        <p class="text-gray-600 px-6 text-center">
                            Plaša produktu izvēle pie konkurētspējīgām cenām.
                        </p>
                    </div>
                    <div
                        class="flex flex-col items-center border-r border-gray-300 py-6"
                    >
                        <div
                            class="bg-primary text-white rounded-full p-4 mb-2"
                        >
                            <i class="fas fa-shipping-fast text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold pb-4">Ātra piegāde</h3>
                        <p class="text-gray-600 text-center">
                            Ātra un uzticama piegāde līdz Jūsu durvīm.
                        </p>
                    </div>
                    <div class="flex flex-col items-center py-6">
                        <div
                            class="bg-primary text-white rounded-full p-4 mb-2"
                        >
                            <i class="fas fa-shield-alt text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold pb-4">
                            Droši maksājumi
                        </h3>
                        <p class="text-gray-600 pb-4 text-center">
                            Jūsu darījumi ir droši ar mūsu drošajiem maksājumu
                            risinājumiem.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <ScrollTopBtn />
        <Footer />
    </div>
</template>
