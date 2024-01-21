<script lang="ts">
    import { goto } from '$app/navigation';
    	import { csrf, getError } from '$lib/api';
        import type { ApiError } from '$lib/models/errors';
        import { user } from '$lib/store';
        import { onDestroy, onMount } from 'svelte';
        import { auth, getPermissions, type LoginResponse } from '$lib/api/auth';
        import { me } from '$lib/api/user';

        const source = new AbortController();

        let apiError: ApiError | null = null;

        onMount(async () => {
		await csrf(source.signal);
        });

        onDestroy(() => {
            source.abort();
        });

        const submit = async (e: Event) => {
		e.preventDefault();

		let res: LoginResponse | undefined = undefined;
        let email = '';
	    let password = '';
		
        try {
			res = await auth(source.signal, {
				email,
				password,
			});

			const currentUser = await me(source.signal);
            const permissions = await getPermissions(source.signal); 

			$user = {
				type: 'authenticated',
				user: currentUser.data,
                permissions: permissions
			};

			goto('/');
		} catch (err) {
			// @ts-ignore
			apiError = await getError(err.response);
		}
	};
</script>
<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        
        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    Sign in to your account
                </h1>
                <form class="space-y-6" method="POST" on:submit={submit}>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                        <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" >
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div class="flex items-center justify-between">
                        <a href="#" class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">Forgot password?</a>
                    </div>
                    <button type="submit" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">Sing in</button>
                </form>
            </div>
        </div>
        {#if apiError !== null}
		<div
			class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
			role="alert"
		>
			{apiError.message}
		</div>
	{/if}
    </div>
  </section>