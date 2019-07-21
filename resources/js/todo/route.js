import Vue from "vue";
import Router from "vue-router";

import home from "./components/Home/index";

Vue.use(Router);

const router = new Router({
	mode: 'history',

	routes: [
		{
			path: '/todo',
			name: 'index',
			component: home,
		},
	]
});

export default router;