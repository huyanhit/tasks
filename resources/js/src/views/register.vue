<template>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center  px-6 py-8 mx-auto md:h-screen lg:py-0">
            <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Sign in to your account
                    </h1>
                    <form class="space-y-4 md:space-y-6" action="#">
                        <div class="text-red-500">{{data.error}}</div>
                        <div :class="{ error: v$.name.$errors.length }">
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your name </label>
                            <input type="text" v-model="data.form.name"  class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name" required="">
                            <div class="input-errors" v-for="error of v$.name.$errors" :key="error.$uid">
                                <div class="error-msg text-red-500 text-xs">{{ error.$message }}</div>
                            </div>
                        </div>
                        <div :class="{ error: v$.email.$errors.length }">
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                            <input type="email" v-model="data.form.email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required="">
                            <div class="input-errors" v-for="error of v$.email.$errors" :key="error.$uid">
                                <div class="error-msg text-red-500 text-xs">{{ error.$message }}</div>
                            </div>
                        </div>
                        <div :class="{ error: v$.password.$errors.length }">
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input type="password" name="password" v-model="data.form.password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                            <div class="input-errors" v-for="error of v$.password.$errors" :key="error.$uid">
                                <div class="error-msg text-red-500 text-xs">{{ error.$message }}</div>
                            </div>
                        </div>
                        <div :class="{ error: v$.password_confirmation.$errors.length }">
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
                            <input type="password" name="password" v-model="data.form.password_confirmation" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                            <div class="input-errors" v-for="error of v$.password_confirmation.$errors" :key="error.$uid">
                                <div class="error-msg text-red-500 text-xs">{{ error.$message }}</div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button @click.prevent="registerApp" class="text-white bg-blue-950 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Register</button>
                        </div>
                        <p class="text-center text-sm font-light text-gray-500 dark:text-gray-400">
                            You have an account <a href="/form" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Sign in</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>
<script setup>
import {computed, reactive, ref} from 'vue';
    import {callApi} from "@/composables/use-api";
    import router from "@/router";
    import { required, email, sameAs } from '@vuelidate/validators'
    import useVuelidate from "@vuelidate/core";

    const data = reactive({
            error: '',
            showPass: false,
            showAlert: false,
            apiMessage: '',
            form:{
                name:'',
                email: '',
                password: '',
                password_confirmation: '',
            },
            rules: {
                name:{ required },
                email: { required, email },
                password: { required },
                password_confirmation: { required, v: sameAs(computed(() => data.form.password))}
            }
        }
    )

    const v$ = useVuelidate(data.rules, data.form)
    const registerApp = async function(e){
        if(await v$.value.$validate()){
            callApi('register', 'POST', JSON.stringify(data.form)).then(response=>{
                if(response.status === 1){
                    data.error = '';
                    localStorage.setItem('auth', JSON.stringify(response.data));
                    router.push({ path: 'task' })
                }else{
                    data.error = response.message;
                }
            });
        }
    }
</script>

