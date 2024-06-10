<script setup>
import { ref, computed, reactive, watch } from "vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import Navbar from "../Components/Navbar.vue";
import Footer from "../Components/Footer.vue";
import Breadcrumbs from "../Components/Breadcrumbs.vue";
import ScrollTopBtn from "../Components/ScrollToTopBtn.vue";
import { Button, InputNumber, message, Steps } from "ant-design-vue";

const { Step } = Steps;

const props = defineProps({
    cartItems: Array,
});

const quantities = reactive({});

props.cartItems.forEach((item) => {
    quantities[item.id] = item.quantity;
});

const totalPrice = computed(() => {
    return props.cartItems
        .reduce((sum, item) => {
            return (
                sum +
                (item.good
                    ? parseFloat(item.good.price) * quantities[item.id]
                    : 0)
            );
        }, 0)
        .toFixed(2);
});

const formattedPrice = (price) => {
    return parseFloat(price).toFixed(2);
};

const redirectToCheckout = async () => {
    const items = props.cartItems.map((item) => ({
        id: item.id,
        quantity: quantities[item.id],
    }));

    console.log('Items to update:', items); // Log the items being sent

    try {
        await router.patch(route("cart.updateAndCheckout"), {
            items,
        }).then(() => {
            // Redirect to checkout page only after the patch request completes
            router.visit(route("checkout.index"));
        });
    } catch (error) {
        console.error("Failed to update cart and proceed to checkout:", error);
    }
};

const updateQuantity = (item, value) => {
    quantities[item.id] = value;
    // Optionally, you can call your API to update the quantity
    console.log(`Update quantity for item ${item.id} to ${value}`);
};

const removeItem = async (item) => {
    try {
        await router.delete(route("cart.remove", item.id));
        // Optionally, you can refresh the page or remove the item from the cartItems array
        message.success("Dzēsta prece no groza!");
        console.log(`Removed item ${item.id}`);
    } catch (error) {
        console.error(`Failed to remove item ${item.id}:`, error);
    }
};
</script>

<template>
    <Head title="GROZS" />

    <Navbar />
    <ScrollTopBtn />
    <div class="cross-patternSVGLight bg-whiter">
        <div class="py-8">
            <Breadcrumbs currentPage="Grozs" />
        </div>

        <div class="space-y-12 container mx-auto">
            <div
                class="container max-w-screen-2xl border border-gray-200 rounded-xl bg-whiter shadow-md py-5 px-8"
            >
                <Steps current="1" class="cursor-none">
                    <Step
                        title="Grozs"
                        description="Preču pievienošana grozam"
                    />
                    <Step
                        title="Norēķins"
                        description="Kontaktinformācijas ievade"
                    />
                    <Step title="Gaidām" description="Pasūtījuma statuss" />
                </Steps>
            </div>
            <div
                v-if="props.cartItems.length === 0"
                class="flex justify-center items-center"
            >
                <div
                    class="container max-w-screen-2xl border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6 h-[500px] flex justify-center items-center"
                >
                    <div class="flex items-center space-x-3 select-none">
                        <div class="bg-gray-200 p-4 rounded-full">
                            <i
                                class="fas fa-shopping-cart text-4xl text-gray-300"
                            ></i>
                        </div>
                        <span class="text-2xl text-gray-300"
                            >Grozs ir tukšs! Ejam iepirkties!</span
                        >
                    </div>
                </div>
            </div>
            <div v-else>
                <div class="w-full">
                    <div
                        class="container border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6"
                    >
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-textColor">
                                    <th class="py-2 px-4">Attēls</th>
                                    <th class="py-2 px-4">Nosaukums</th>
                                    <th class="py-2 px-4">Skaits</th>
                                    <th class="py-2 px-4">Cena (gab)</th>
                                    <th class="py-2 px-4">Cena</th>
                                    <th class="py-2 px-4">Darbības</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="item in props.cartItems"
                                    :key="item.id"
                                    class=""
                                >
                                    <td class="py-2 px-4">
                                        <img
                                            v-if="item.good && item.good.image"
                                            :src="item.good.image"
                                            alt="Preces attēls"
                                            class="w-20 h-20 rounded-md"
                                        />
                                    </td>
                                    <td class="py-2 px-4">
                                        <span v-if="item.good" class="">{{
                                            item.good.name
                                        }}</span>
                                    </td>
                                    <td class="py-2 px-4">
                                        <InputNumber
                                            :min="1"
                                            v-model:value="quantities[item.id]"
                                            @change="
                                                (value) =>
                                                    updateQuantity(item, value)
                                            "
                                        />
                                    </td>
                                    <td class="py-2 px-4">
                                        <span v-if="item.good"
                                            >€{{
                                                formattedPrice(item.good.price)
                                            }}</span
                                        >
                                    </td>
                                    <td class="py-2 px-4">
                                        <span v-if="item.good"
                                            >€{{
                                                formattedPrice(
                                                    item.good.price *
                                                        quantities[item.id]
                                                )
                                            }}</span
                                        >
                                    </td>
                                    <td class="py-2 px-4">
                                        <button
                                            class="bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-700 rounded-xl p-2 px-3"
                                            @click="() => removeItem(item)"
                                        >
                                            <i class="fas fa-xmark"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-4 border-t border-gray-200 pt-4">
                            <h3
                                class="text-lg font-semibold pb-4 text-textColor"
                            >
                                Pasūtījuma kopsavilkums
                            </h3>
                            <div class="text-gray-600">
                                <div
                                    class="flex justify-between py-2 font-bold text-textColor"
                                >
                                    <span>Kopā:</span>
                                    <span>{{ totalPrice }} €</span>
                                </div>
                                <Button
                                    type="primary"
                                    class="w-full text-base font-semibold bg-primary text-white rounded-xl uppercase"
                                    @click="redirectToCheckout"
                                >
                                    Pasūtīt
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
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
