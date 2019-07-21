<template>
    <div class="px-2" v-if="userName">
        <div class="container py-4 flex justify-between">
            <div class="bg-black text-white px-4 py-1 rounded cursor-pointer">Todo</div>

            <div v-if="userName" class="filter-shadow opacity-50 cursor-pointer text-sm pt-1">
                <img class="h-6 rounded-full inline-block" :src="userAvatar">
                <span class="">{{ userName }}</span>
            </div>
        </div>

        <router-view></router-view>
    </div>

    <div v-else class="text-center pt-10 text-gray-600 font-bold text-lg">Loading...</div>
</template>

<script>
	import ApiClient from "../../lib/ApiClient";

	export default {
		name: "App",

        data() {
			return {
				title: 'good',
                userName: false,
                userAvatar: '',
            }
        },

        async mounted() {
			let response;
			try {
				response = (await ApiClient.get('/api/user/info')).data;
			} catch (e) {
                alert('failed to get user\'s information!');
			}

			this.userName = response.name;
			this.userAvatar = response.avatar;
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