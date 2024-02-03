<script lang="ts">
	import { createEventDispatcher, onMount } from 'svelte';
	import MultiValueInput from '$lib/components/inputs/MultiValueInput.svelte';
	import type { PartnerModel } from '$lib/models/partners';
	import type { PartnerRequest } from '$lib/models';

	export let partner: PartnerModel | null = null;

	let addresses: string[] = partner?.info.addresses ?? [];
	let emails: string[] = partner?.info.emails ?? [];
	let phones: string[] = partner?.info.phones ?? [];
	let name: string = partner?.name ?? '';
	let description: string = partner?.description ?? '';

	onMount(() => {
		addresses = partner?.info.addresses ?? [];
		emails = partner?.info.emails ?? [];
		phones = partner?.info.phones ?? [];
		name = partner?.name ?? '';
		description = partner?.description ?? '';
	});

	const dispatch = createEventDispatcher();

	const onSubmit = (e: Event) => {
		e.preventDefault();
		e.stopPropagation();

		const newPartner: PartnerRequest = {
			name,
			description,
			addresses,
			emails,
			phones
		};

		dispatch('submit', newPartner);
	};
</script>

<div>
	<div class="space-y-10 divide-y divide-gray-900/10 mt-5">
		<div class="grid grid-cols-1 gap-y-8 xl:grid-cols-3">
			<div class="px-4 md:px-0 md:pr-3">
				<h2 class="text-base font-semibold leading-7 text-gray-900">Profile</h2>
				<p class="mt-1 text-sm leading-6 text-gray-600">Information about partner.</p>
			</div>

			<div class="bg-white shadow-sm ring-1 ring-gray-900/5 md:rounded-xl md:col-span-2">
				<div class="px-4 py-6 sm:p-8">
					<div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
						<div class="sm:col-span-4">
							<label for="partner_name" class="block text-sm font-medium leading-6 text-gray-900"
								>Partner Name</label
							>
							<div class="mt-2">
								<div
									class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md"
								>
									<input
										type="text"
										bind:value={name}
										name="partner_name"
										id="partner_name"
										class="block flex-1 border-0 bg-transparent p-2 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
										placeholder="Example Partner Name"
									/>
								</div>
							</div>
						</div>

						<div class="col-span-full">
							<label for="about" class="block text-sm font-medium leading-6 text-gray-900"
								>About</label
							>
							<div class="mt-2">
								<textarea
									placeholder=""
									id="about"
									name="about"
									rows="3"
									bind:value={description}
									class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
								/>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="grid grid-cols-1 gap-x-8 gap-y-8 pt-10 xl:grid-cols-3">
			<div class="px-4 md:px-0 md:pr-3">
				<h2 class="text-base font-semibold leading-7 text-gray-900">Partner information</h2>
				<p class="mt-1 text-sm leading-6 text-gray-600">Emails, Phones and Company Addresses.</p>
			</div>

			<div class="bg-white shadow-sm ring-1 ring-gray-900/5 md:rounded-xl md:col-span-2">
				<div class="px-4 py-6 sm:p-8">
					<div class="max-w-full divide-y divide-gray-300 sm:grid-cols-6">
						<div>
							<MultiValueInput
								label="Phones"
								name="phone"
								type="tel"
								placeholder="Enter phone number..."
								bind:values={phones}
							>
								<svg
									xmlns="http://www.w3.org/2000/svg"
									fill="none"
									viewBox="0 0 24 24"
									stroke-width="1.5"
									stroke="currentColor"
									class="w-5 h-5"
								>
									<path
										stroke-linecap="round"
										stroke-linejoin="round"
										d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"
									/>
								</svg>
							</MultiValueInput>
						</div>
						<div class="pt-3">
							<MultiValueInput
								label="Emails"
								name="email"
								type="email"
								placeholder="Enter email..."
								bind:values={emails}
							>
								<svg
									xmlns="http://www.w3.org/2000/svg"
									fill="none"
									viewBox="0 0 24 24"
									stroke-width="1.5"
									stroke="currentColor"
									class="w-6 h-6"
								>
									<path
										stroke-linecap="round"
										stroke-linejoin="round"
										d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"
									/>
								</svg>
							</MultiValueInput>
						</div>
						<div class="pt-3">
							<MultiValueInput
								label="Address"
								name="address"
								placeholder="Enter address..."
								bind:values={addresses}
							>
								<svg
									xmlns="http://www.w3.org/2000/svg"
									fill="none"
									viewBox="0 0 24 24"
									stroke-width="1.5"
									stroke="currentColor"
									class="w-5 h-5"
								>
									<path
										stroke-linecap="round"
										stroke-linejoin="round"
										d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"
									/>
								</svg>
							</MultiValueInput>
						</div>
					</div>
				</div>
				<div
					class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8"
				>
					<a href="/partners" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
					<button
						on:click={onSubmit}
						class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
					>
						Save
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
