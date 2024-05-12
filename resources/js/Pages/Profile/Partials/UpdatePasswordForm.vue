<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>

        <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
            <p v-if="form.recentlySuccessful"
                class="text-lg text-green-500 px-4 py-2 bg-green-100 text-center rounded-lg mb-4 ">Veiksmīgi veiktas
                izmaiņas!
            </p>
        </Transition>

        <header>
            <h2 class="text-lg font-medium text-textColor">Atjaunināt paroli</h2>

            <p class="mt-1 text-sm text-textColor">
                Izvairieties no vienkāršu vai viegli uzminamu paroļu lietošanas, piemēram, dzimšanas datumi vai vārdi.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>

                <TextInput id="current_password" placeholder="Pašreizējā parole" ref="currentPasswordInput" v-model="form.current_password"
                    type="password" class="mt-1 block w-2/4" autocomplete="current-password" />

                <InputError :message="form.errors.current_password" class="mt-2" />
            </div>

            <div>

                <TextInput id="password" ref="passwordInput" placeholder="Jaunā parole" v-model="form.password" type="password"
                    class="mt-1 block w-2/4" autocomplete="new-password" />

                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div>

                <TextInput id="password_confirmation" placeholder="Jaunā parole atkārtoti" v-model="form.password_confirmation" type="password"
                    class="mt-1 block w-2/4" autocomplete="new-password" />

                <InputError :message="form.errors.password_confirmation" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" :disabled="form.processing"
                    class="bg-primary rounded-xl hover:bg-accent text-white py-2 px-4 hover:scale-105 duration-200 text-md uppercase">
                    Saglabāt </button>

            </div>
        </form>
    </section>
</template>
