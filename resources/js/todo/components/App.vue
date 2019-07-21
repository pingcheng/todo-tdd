<template>
    <div class="px-2">
        <div class="container py-4 flex justify-between">
            <div class="bg-black text-white px-4 py-1 rounded cursor-pointer">Todo</div>

            <div class="filter-shadow opacity-50 cursor-pointer text-sm pt-1">
                <img class="h-6 rounded-full inline-block" src="https://lh5.googleusercontent.com/-b0On2el7UEA/AAAAAAAAAAI/AAAAAAAAAT4/iN-Eb_Z0JOc/photo.jpg">
                <span class="">Ping Cheng</span>
            </div>
        </div>

        <router-view></router-view>
    </div>
</template>

<script>
	export default {
		name: "App",

        data() {
			return {
				title: 'good',
            }
        }
	}
</script>

<style scoped>
    .filter-shadow {
        filter: drop-shadow(0 3px 4px rgba(0, 0, 0, 0.3));
        transition: all 0.3s;
    }
    .filter-shadow:hover {
        opacity: 1;
    }
</style>