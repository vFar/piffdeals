<script setup>
import { ref, computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import Navbar from "../Components/Navbar.vue";
import Footer from "../Components/Footer.vue";
import ScrollToTopBtn from "../Components/ScrollToTopBtn.vue";
import {
    Button,
    InputNumber,
    message,
    Steps,
    Alert,
    Select,
    Image,
} from "ant-design-vue";
import Breadcrumbs from "../Components/Breadcrumbs.vue";

const { Step } = Steps;

const props = defineProps({
    cartItems: Array,
});

console.log(props.cartItems)
const totalPrice = computed(() => {
    return props.cartItems
        .reduce((sum, item) => {
            return (
                sum +
                (item.good ? parseFloat(item.good.price) * item.quantity : 0)
            );
        }, 0)
        .toFixed(2);
});

// Form data
const form = useForm({
    name: "",
    phone_number: "",
    street: "",
    city: "",
    postal_code: "",
});

const handleSubmit = () => {
    form.post(route("checkout.store"), { // Make sure this route is correctly aliased in your Laravel route definitions
        preserveScroll: true,
        onSuccess: () => {
            message.success("Order created successfully");
            router.replace(route("checkout.index")); // Redirect or update UI upon success
        },
        onError: error => {
            console.log(error);
            message.error("Failed to create order"); // More detailed error handling might be necessary
        }
    });
};


// City options
const cities = [
    "Rīga",
    "Daugavpils",
    "Liepāja",
    "Jelgava",
    "Jūrmala",
    "Ādažu nov.",
    "Aglonas nov.",
    "Ainaži",
    "Aizkraukle",
    "Aizkraukles nov.",
    "Aizpute",
    "Aizputes nov.",
    "Aknīste",
    "Aknīstes nov.",
    "Aloja",
    "Alojas nov.",
    "Alsungas nov.",
    "Alūksne",
    "Alūksnes nov.",
    "Amatas nov.",
    "Ape",
    "Apes nov.",
    "Auce",
    "Auces nov.",
    "Babītes nov.",
    "Baldone",
    "Baldones nov.",
    "Baloži",
    "Baltinavas nov.",
    "Balvi",
    "Balvu nov.",
    "Bauska",
    "Bauskas nov.",
    "Beverīnas nov.",
    "Brocēni",
    "Brocēnu nov.",
    "Burtnieku nov.",
    "Carnikavas nov.",
    "Cēsis",
    "Cēsu nov.",
    "Cesvaine",
    "Cesvaines nov.",
    "Ciblas nov.",
    "Dagda",
    "Dagdas nov.",
    "Daugavpils nov.",
    "Dobele",
    "Dobeles nov.",
    "Dundagas nov.",
    "Durbe",
    "Durbes nov.",
    "Engures nov.",
    "Ērgļu nov.",
    "Garkalnes nov.",
    "Grobiņa",
    "Grobiņas nov.",
    "Gulbene",
    "Gulbenes nov.",
    "Iecavas nov.",
    "Ikšķile",
    "Ikšķiles nov.",
    "Ilūkste",
    "Ilūkstes nov.",
    "Inčukalna nov.",
    "Jaunjelgava",
    "Jaunjelgavas nov.",
    "Jaunpiebalgas nov.",
    "Jaunpils nov.",
    "Jēkabpils",
    "Jēkabpils nov.",
    "Jelgavas nov.",
    "Kandava",
    "Kandavas nov.",
    "Kārsava",
    "Kārsavas nov.",
    "Kocēnu nov.",
    "Kokneses nov.",
    "Krāslava",
    "Krāslavas nov.",
    "Krimuldas nov.",
    "Krustpils nov.",
    "Kuldīga",
    "Kuldīgas nov.",
    "Ķeguma nov.",
    "Ķegums",
    "Ķekavas nov.",
    "Lielvārde",
    "Lielvārdes nov.",
    "Līgatne",
    "Līgatnes nov.",
    "Limbaži",
    "Limbažu nov.",
    "Līvāni",
    "Līvānu nov.",
    "Lubāna",
    "Lubānas nov.",
    "Ludza",
    "Ludzas nov.",
    "Madona",
    "Madonas nov.",
    "Mālpils nov.",
    "Mārupes nov.",
    "Mazsalaca",
    "Mazsalacas nov.",
    "Mērsraga nov.",
    "Naukšēnu nov.",
    "Neretas nov.",
    "Nīcas nov.",
    "Ogre",
    "Ogres nov.",
    "Olaine",
    "Olaines nov.",
    "Ozolnieku nov.",
    "Pārgaujas nov.",
    "Pāvilosta",
    "Pāvilostas nov.",
    "Piltene",
    "Pļaviņas",
    "Pļaviņu nov.",
    "Preiļi",
    "Preiļu nov.",
    "Priekule",
    "Priekules nov.",
    "Priekuļu nov.",
    "Raunas nov.",
    "Rēzekne",
    "Rēzeknes nov.",
    "Riebiņu nov.",
    "Rojas nov.",
    "Ropažu nov.",
    "Rucavas nov.",
    "Rugāju nov.",
    "Rūjiena",
    "Rūjienas nov.",
    "Rundāles nov.",
    "Sabile",
    "Salacgrīva",
    "Salacgrīvas nov.",
    "Salas nov.",
    "Salaspils",
    "Salaspils nov.",
    "Saldus",
    "Saldus nov.",
    "Saulkrasti",
    "Saulkrastu nov.",
    "Seda",
    "Sējas nov.",
    "Sigulda",
    "Siguldas nov.",
    "Skrīveru nov.",
    "Skrunda",
    "Skrundas nov.",
    "Smiltene",
    "Smiltenes nov.",
    "Staicele",
    "Stende",
    "Stopiņu nov.",
    "Strenči",
    "Strenču nov.",
    "Subate",
    "Talsi",
    "Talsu nov.",
    "Tērvetes nov.",
    "Tukums",
    "Tukuma nov.",
    "Vaiņodes nov.",
    "Valdemārpils",
    "Valka",
    "Valkas nov.",
    "Valmiera",
    "Vangaži",
    "Varakļāni",
    "Varakļānu nov.",
    "Vārkavas nov.",
    "Vecpiebalgas nov.",
    "Vecumnieku nov.",
    "Ventspils",
    "Ventspils nov.",
    "Viesīte",
    "Viesītes nov.",
    "Viļaka",
    "Viļakas nov.",
    "Viļāni",
    "Viļānu nov.",
    "Zilupe",
    "Zilupes nov.",
];

const cityOptions = cities.map((city) => ({ value: city, label: city }));
</script>

<template>
    <Head title="NORĒĶINĀTIES" />

    <Navbar />
    <ScrollToTopBtn />
    <div class="cross-patternSVGLight bg-whiter">
        <div class="py-8">
            <Breadcrumbs currentPage="Norēķins" />
        </div>

        <div class="space-y-12 container mx-auto">
            <div
                class="container max-w-screen-2xl border border-gray-200 rounded-xl bg-whiter shadow-md py-5 px-8"
            >
                <Steps current="2" class="cursor-none">
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

            <div class="lg:flex lg:items-start lg:gap-12 xl:gap-16">
                <div
                    class="w-full lg:w-2/3 space-y-8 bg-whiter shadow-md rounded-xl"
                >
                    <div class="space-y-4 px-12 py-6">
                        <h2
                            class="text-xl font-semibold text-gray-900 dark:text-white uppercase"
                        >
                            Pircēja kontaktinformācija
                        </h2>
                        <form @submit.prevent="handleSubmit" class="space-y-4">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label
                                        for="name"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
                                        >Vārds, uzvārds</label
                                    >
                                    <Input
                                        v-model="form.name"
                                        id="name"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                        required
                                    />
                                </div>
                                <div>
                                    <label
                                        for="phone_number"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
                                        >Telefona numurs</label
                                    >
                                    <InputNumber
                                        :controls="false"
                                        v-model="form.phone_number"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                        required
                                    />
                                </div>
                                <div>
                                    <label
                                        for="city"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
                                        >Pilsēta</label
                                    >
                                    <Select
                                        v-model:value="form.city"
                                        show-search
                                        placeholder="Izvēlieties pilsētu"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                        :options="cityOptions"
                                        filter-option
                                    />
                                </div>
                                <div>
                                    <label
                                        for="street"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
                                        >Iela</label
                                    >
                                    <Input
                                        v-model="form.street"
                                        id="street"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                        required
                                    />
                                </div>

                                <div>
                                    <label
                                        for="postal_code"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
                                        >Pasta indekss</label
                                    >
                                    <Input
                                        v-model="form.postal_code"
                                        id="postal_code"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                        required
                                    />
                                </div>
                            </div>

                            <Alert
                                message="Mēs apņemamies nodrošināt Jūsu sniegtās informācijas konfidencialitāti. "
                                description="Jūsu kontaktinformācija tiks izmantota tikai, lai identificētu Jūs kā pircēju mūsu tirdzniecības sistēmā un, lai nodrošinātu preču piegādi."
                                type="warning"
                                show-icon
                            />

                            <div class="mt-4">
                                <Button type="primary" class="w-full uppercase"
                                    >Pasūtīt</Button
                                >
                            </div>
                        </form>
                    </div>
                </div>
                <div class="w-full lg:w-1/3 mt-6 lg:mt-0 space-y-6">
                    <div class="bg-whiter rounded shadow-xl p-6">
                        <h2 class="text-xl uppercase font-semibold mb-4">
                            Pasūtījuma kopsavilkums
                        </h2>
                        <ul>
                            <li
                                v-for="item in cartItems"
                                :key="item.id"
                                class="mb-4 flex items-center space-x-4"
                            >
                                <div class="relative">
                                    <Image
                                        v-if="item.good && item.good.image"
                                        :src="item.good.image"
                                        alt="Preces attēls"
                                        class="rounded-md cursor-pointer"
                                        width="64px"
                                        height="64px"
                                        previewMask="Priekšskatījums"
                                    />
                                    <span
                                        class="absolute bottom-[50px] left-[50px] bg-red-600 font-semibold text-white text-xs rounded-md p-1"
                                    >
                                        {{ item.quantity }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between">
                                        <span>
                                            {{ item.good.name }}
                                        </span>
                                        <span>
                                            {{
                                                (
                                                    item.good.price *
                                                    item.quantity
                                                ).toFixed(2)
                                            }}
                                            €
                                        </span>
                                    </div>
                                </div>
                            </li>
                        </ul>

                        <div class="mt-4 border-t border-gray-200 pt-4">
                            <div class="flex justify-between py-2 font-bold">
                                <span>Kopā:</span>
                                <span>{{ totalPrice }} €</span>
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
