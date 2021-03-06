<template>
    <div>

        <div v-if="loaded">
            <div class="mb-4">
                <label class="text-sm text-gray-500 font-bold">Add a new todo</label>
                <input class="border border-gray-400 focus:border-blue-400 p-3 focus:outline-none block w-full text-gray-700" placeholder="Enter your todo here..." v-model="newTodo" @keyup.enter="addTodo">
            </div>

            <div class="mb-4">
                <label class="text-sm text-gray-500 font-bold">Todo</label>

                <div>
                    <ul class="nav">
                        <li :class="{active: tab === 'todo'}" @click="switchTab('todo')">Todo</li>
                        <li :class="{active: tab === 'done'}" @click="switchTab('done')">Done</li>
                    </ul>
                </div>

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
                tab: 'todo',
				newTodo: '',
				todoItems: []
            }
        },

        methods: {
			switchTab(tab) {
				this.tab = tab;
				this.loadTodoItems();
            },

			async toggleTodoCompletion(index) {
				let oldStatus = this.todoItems[index].done;
				let changeTo = oldStatus ? 'undone' : 'done';
				let response;

				this.todoItems[index].done = !oldStatus;

				try {
					response = await ApiClient.post(`/api/todo/${this.todoItems[index].id}/${changeTo}`)
                } catch (e) {
                    alert(e.data);
					this.todoItems[index].done = oldStatus;
                    return;
				}

				console.log(response);

				if (response.status_code !== 200) {
					this.todoItems[index].done = oldStatus;
                }
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
                    if (this.tab === 'todo') {
                        response = (await ApiClient.get('/api/todo')).data;
                    } else {
                        response = (await ApiClient.get('/api/todo?filter=done')).data;
                    }
                } catch (e) {
                    alert('load todo list failed: ' + e.data);
                }

                for (let item of response) {
                    todo.push({
                        id: item.id,
                        content: item.content,
                        done: item.done_at > 0,
                        done_at: item.done_at,
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
    .nav {
        @apply flex my-4;
    }

    .nav li {
        @apply mr-3 px-2 py-1 cursor-pointer rounded;
    }

    .nav li:hover {
        @apply bg-blue-200;
    }

    .nav li.active {
        @apply bg-blue-400 text-white;
    }
</style>