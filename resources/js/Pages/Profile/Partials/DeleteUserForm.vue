<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>

            <p class="mt-1 text-sm text-textColor">
                Dzēšot savu lietotāja kontu, visi dati datubāzē tiks anonimizēti uz nejaušiem burtiem un skaitļiem. Kontu atgūt nebūs iespējams!
            </p>
        </header>

        
        <button type="button" @click="confirmUserDeletion"
                    class="bg-red-600 rounded-xl hover:bg-red-500 text-white py-2 px-4 hover:scale-105 duration-200 text-md uppercase">
                    Dzēst kontu </button>


        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-textColor">
                    Vai tiešām vēlies dzēst savu kontu?
                </h2>

                <p class="mt-1 text-sm text-textColor">
                    Dzēšot kontu, visu to dati būs pārģenēreti uz nejaušiem burtiem un skaitļiem! Lūdzu, ievadiet savu paroli, lai apstiprinātu dzēst savu kontu.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Konta parole" class="sr-only" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Parole"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Atcelt </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Dzēst kontu
                    </DangerButton>
                

                </div>
            </div>
        </Modal>
    </section>
</template>
