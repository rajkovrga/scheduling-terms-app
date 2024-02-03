<script lang="ts">
	import { goto } from '$app/navigation';
	import { deletePartner } from '$lib/api/partner';
	import type { PartnerModel } from '$lib/models/partners';
	import { createEventDispatcher, onDestroy } from 'svelte';

	export let data: PartnerModel;

	const abortController = new AbortController();
	const dispatcher = createEventDispatcher();

	onDestroy(() => {
		abortController.abort();
	});

	const remove = async (e: Event) => {
		e.preventDefault();
		e.stopPropagation();
		await deletePartner(abortController.signal, data.id);
		dispatcher('removed', data);
	};

	const viewItem = async (e: Event) => {
		e.preventDefault();
		e.stopPropagation();
		await goto(`/partners/edit/${data.id}`);
	};
</script>

<tr class="even:bg-gray-50"
	on:click={viewItem}
>
	<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">Lindsay Walton</td>
	<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">lindsay.walton@example.com</td>
	<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Member</td>
	<td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
	  <a href="#" class="text-indigo-600 hover:text-indigo-900" on:click={remove}>Remove<span class="sr-only">, Lindsay Walton</span></a>
	</td>
</tr>