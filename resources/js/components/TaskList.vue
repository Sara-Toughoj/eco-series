<template>
    <div>
        <ul>
            <li v-for="task in tasks" v-text="task"></li>
        </ul>
        <label>
            <input type="text" v-model="newTask" @blur="addTask">
        </label>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                tasks: [],
                newTask: ""
            };
        },
        created() {
            axios.get('tasks').then(response =>
                (this.tasks = response.data)
            )

            window.Echo.channel('tasks').listen('TaskCreated', e => {
                this.tasks.push(e.task.body);
            });
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            addTask() {
                axios.post('tasks', {body: this.newTask}).then((response) => {
                    this.tasks.push(this.newTask);
                    this.newTask = '';
                });
            }
        }
    }
</script>
