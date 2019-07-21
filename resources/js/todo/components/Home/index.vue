<template>
    <div>
        <div class="mb-4">
            <label class="text-sm text-gray-500 font-bold">Add a new todo</label>
            <input class="border border-gray-400 focus:border-blue-400 p-3 focus:outline-none block w-full text-gray-700" placeholder="Enter your todo here..." v-model="newTodo" @keyup.enter="addTodo">
        </div>

        <div class="mb-4">
            <label class="text-sm text-gray-500 font-bold">Todo</label>

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
                        {{ todo.text }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	export default {
		name: "index",

        data() {
			return {
				newTodo: '',
				todoItems: [
                    {
                    	text: 'hello',
                        done: false,
                    },
                    {
                    	text: 'tomorrow is a good thing',
                        done: false,
                    },
                    {
                    	text: 'how are you',
                        done: true
                    }
                ]
            }
        },

        methods: {
			toggleTodoCompletion(index) {
				this.todoItems[index].done = !this.todoItems[index].done;
				console.log(index);
            },

            addTodo() {
				let todo = this.newTodo.trim();

				if (todo === '') {
					return;
                }

				this.todoItems.push({
                    text: todo,
                    done: false,
                });

				this.newTodo = '';
            },

            deleteTodo(index) {
				this.todoItems.splice(index, 1);
            },
        }
	}
</script>

<style scoped>

</style>