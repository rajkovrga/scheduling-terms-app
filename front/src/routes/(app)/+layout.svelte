<script lang="ts">
    import { page } from '$app/stores';
    import '../../app.css';
    import Navbar from '$lib/components/nav/Navbar.svelte';
</script>

<svelte:head>
	<title>{$page.data.title}</title>
</svelte:head>
<div class="w-full">
    <Navbar>
        <slot></slot>
    </Navbar>    
</div>