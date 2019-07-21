<template>
    <div>

        <div v-if="loaded">
            <div class="mb-4">
                <label class="text-sm text-gray-500 font-bold">Add a new todo</label>
                <input class="border border-gray-400 focus:border-blue-400 p-3 focus:outline-none block w-full text-gray-700" placeholder="Enter your todo here..." v-model="newTodo" @keyup.enter="addTodo">
            </div>

            <div class="mb-4">
                <label class="text-sm text-gray-500 font-bold">Todo</label>

                <div v-if="todoItems.length > 0">
                    <div v-for="(todo, index) in todoItems" :key="index">
                        <div class="border border-gray-400 p-3 mb-1 bg-white hover:bg-blue-100 cursor-pointer" @click="toggleTodoCompletion(index)">

                            <div class="float-right" :class="{
                                'text-gray-500': todo.done
                            }">
                                <i class="fas fa-trash" @click.stop="deleteTodo(index)"></i>
                            </div>

                            <div :class="{
                                'text-gray-500': todo.done
                            }">
                                <i v-if="!todo.done" class="far fa-square mr-2"></i>
                                <i v-else class="far fa-check-square mr-2"></i>
                                {{ todo.content }}
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="text-gray-700">
                    No todo items yet! Add one now!
                </div>
            </div>
        </div>

        <div v-else class="text-center pt-10 text-gray-600 font-bold text-lg">Loading...</div>
    </div>
</template>

<script>
	import ApiClient from "../../../lib/ApiClient";

	export default {
		name: "index",

        data() {
			return {
				loaded: false,
				newTodo: '',
				todoItems: []
            }
        },

        methods: {
			toggleTodoCompletion(index) {
				this.todoItems[index].done = !this.todoItems[index].done;
            },

            async addTodo() {
				let todo = this.newTodo.trim();

				if (todo === '') {
					return;
                }

                try {
					await ApiClient.post('/api/todo', {
						content: todo
                    });
                } catch (e) {
                    alert(e.data);
                }

				this.newTodo = '';
				this.loadTodoItems();
            },

            async deleteTodo(index) {
				let item = this.todoItems[index];

				try {
					ApiClient.delete(`/api/todo/${item.id}`);
                } catch (e) {
                    alert(e.data);
				}

				this.loadTodoItems();
            },

            async loadTodoItems() {
				let response;
				let todo = [];

                try {
					response = (await ApiClient.get('/api/todo')).data;
                } catch (e) {
                    alert('load todo list failed: ' + e.data);
                }

                for (let item of response) {
                    todo.push({
                        id: item.id,
                        content: item.content,
                        done: false,
                    })
                }

                this.todoItems = todo;
                this.loaded = true;
            },
        },

        mounted() {
			this.loadTodoItems();
        }
	}
</script>

<style scoped>

</style>