<script setup>
import { Head, Link } from "@inertiajs/vue3";
import Navbar from "../Components/Navbar.vue";
import Footer from "../Components/Footer.vue";
import ScrollTopBtn from "../Components/ScrollToTopBtn.vue";
import { ref, onMounted, h, onBeforeUnmount } from "vue";
import { usePage } from "@inertiajs/vue3";
import { Carousel, Image, Button, Card, notification } from "ant-design-vue";
import { router } from "@inertiajs/vue3";

// const getImageUrl = (filename) => `/storage/images/${filename}`;

const getImageUrl = (filename) => {
    return `/storage/images/media/${filename}`;
};

const flashMessage = ref("");
const page = usePage();
const auth = page.props.auth;

const carouselImages = ref([
    getImageUrl("1.png"),
    getImageUrl("2.png"),
    getImageUrl("3.png"),
    getImageUrl("4.png"),
    getImageUrl("5.png"),
    getImageUrl("6.png"),
]);

const activeGoods = ref([]);

const fetchActiveGoods = async () => {
    try {
        const response = await fetch("/navigation-data/active-goods");
        if (response.ok) {
            const data = await response.json();
            activeGoods.value = data.goods.map((good) => ({
                id: good.id,
                name: good.name,
                description: good.description,
                price: good.price,
                image: good.image, // Assuming `image` is the attribute name in your Good model
            }));
        } else {
            console.error("Failed to fetch goods data:", response.statusText);
        }
    } catch (error) {
        console.error("Error fetching goods data:", error);
    }
};

let notificationInstance = null;

const showNotification = () => {
    notificationInstance = notification.warning({
        message: "E-pasts nav verificēts!",
        description:
            "Jūsu e-pasts nav verificēts! Lūdzu, verificējiet savu e-pastu, lai varētu izmantot visas funkcijas. Ja e-pasts nav verificēts, tad Jūs nevarēsiet pievienot preces grozā.",
        btn: () =>
            h(
                "button",
                {
                    class: "before:ease relative h-10 w-36 overflow-hidden border border-secondary bg-primary text-white shadow-2xl transition-all before:absolute before:right-0 before:top-0 before:h-12 before:w-6 before:translate-x-12 before:rotate-6 before:bg-white before:opacity-10 before:duration-700 hover:shadow-primary hover:before:-translate-x-40",
                    onClick: () => router.visit(route("verification.notice")),
                },
                "Verificēt tagad"
            ),
        duration: 5, // The notification will not auto close
        placement: "topRight",
    });
};

onMounted(async () => {
    await fetchActiveGoods(); // Fetch the active goods when the component is mounted
    const storedCookies = localStorage.getItem("cookies");
    if (storedCookies) {
        cookies.value = storedCookies;
    }

    if (auth && auth.user && !auth.user.email_verified_at) {
        setTimeout(() => {
            showNotification();
        }, 1000); // Show notification 1 second after page load
    }
});

onBeforeUnmount(() => {
    if (notificationInstance) {
        notificationInstance.close();
    }
});

const previewGood = (goodId) => {
    router.visit(route("goods.show", { id: goodId })); // Redirect to the preview page
};

// Define a variable to store cookies status
const cookies = ref("notsigned");

// Method to close cookies banner
const closeCookiesBanner = () => {
    cookies.value = "signed";
    localStorage.setItem("cookies", "signed");
};
</script>

<style scoped>
.carousel-image {
    width: 100%;
    height: 50vh; /* 50% of the viewport height */
    object-fit: cover;
}

@media (max-width: 768px) {
    .carousel-image {
        height: 30vh;
    }
}

.ant-card-cover {
    position: relative;
    padding-bottom: 75%; /* Adjust percentage for desired aspect ratio */
}

.ant-card-cover img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>

<template>
    <Head title="Veikals" />
    <Navbar />
    <ScrollTopBtn />
    <div class="cross-patternSVGLight bg-whiter">
        <div class="space-y-12">
            <Carousel
                class="select-none"
                autoplay
                effect="fade"
                dotPosition="bottom"
                style="border: none"
                :autoplaySpeed="3000"
            >
                <div
                    v-for="(img, index) in carouselImages"
                    :key="index"
                    class="carousel-item"
                >
                    <Image
                        :src="img"
                        alt="Carousel image"
                        class="carousel-image shadow-md"
                        :preview="false"
                        width="100%"
                    />
                </div>
            </Carousel>
            <div
                class="container max-w-screen-2xl border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6"
            >
                <h1 class="text-textColor font-semibold">Jaunākās preces</h1>

                <div
                    class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 py-4"
                >
                    <div
                        v-for="item in activeGoods"
                        :key="item.id"
                        class="p-4 cursor-pointer border border-gray-200 shadow-md"
                    >
                        <Link
                            :href="route('goods.show', { id: item.id })"
                            class="block"
                        >
                            <div
                                class="flex justify-center items-center overflow-hidden rounded-lg pointer"
                            >
                                <Image
                                    :src="item.image"
                                    alt="goods-image"
                                    class="object-cover transform transition-transform duration-300 hover:scale-110 rounded-lg"
                                    fallback="/images/S-1.png"
                                    :preview="false"
                                    height="200px"
                                    width="200px"
                                />
                            </div>
                            <div>
                                <h2 class="text-md mt-2 text-textColor">
                                    {{ item.name }}
                                </h2>
                                <p
                                    class="text-textColor text-2xl font-semibold"
                                >
                                    <span
                                        class="hover:text-primary transform transition-transform duration-300 hover:scale-110"
                                        >€ {{ item.price }}</span
                                    >
                                </p>
                            </div>
                        </Link>
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

            <section
                v-if="cookies === 'notsigned'"
                class="fixed bottom-0 w-full bg-gray-200 dark:bg-gray-800 shadow-t-md"
            >
                <div
                    class="max-w-5xl px-4 py-8 mx-auto md:flex md:items-center md:gap-x-6"
                >
                    <div class="flex items-start md:items-center gap-x-2">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-6 h-6 text-gray-500 shrink-0 dark:text-gray-300"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"
                            />
                        </svg>

                        <p class="text-gray-700 dark:text-gray-200">
                            Mēs izmantojam sīkdatnes, lai uzlabotu Jūsu
                            lietotāja pieredzi. Turpinot apmeklēt šo vietni, Jūs
                            piekrītat
                            <Link
                                href="/cookies-information"
                                class="underline transition-colors duration-200 hover:text-primary"
                            >
                                mūsu sīkdatņu izmantošanai </Link
                            >.
                        </p>
                    </div>

                    <button
                        class="items-center justify-center hidden text-gray-700 transition-colors duration-300 rounded-full md:flex dark:text-gray-200 dark:hover:bg-gray-700 w-7 h-7 focus:outline-none hover:bg-gray-200"
                        @click="closeCookiesBanner"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            class="w-5 h-5"
                        >
                            <path
                                d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"
                            />
                        </svg>
                    </button>
                </div>
            </section>
        </div>

        <ScrollTopBtn />
        <Footer />
    </div>
</template>
