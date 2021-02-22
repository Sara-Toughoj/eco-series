<template>
    <div>
        <h1> {{ project.name }}</h1>
        <ul>
            <li v-for="task in project.tasks" v-text="task.body"></li>
        </ul>
        <label>
            <input type="text" v-model="newTask" @blur="addTask" style="background-color: red">
        </label>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                project: this.projectdata,
                newTask: ""
            };
        },
        props: {
            projectdata: {
                required: true
            }
        },
        created() {
            window.Echo.channel('tasks.' + this.project.id).listen('TaskCreated', e => {
                this.project.tasks.push(e.task);
            });
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            addTask() {
                axios.post(`/api/projects/${this.project.id}/tasks`, {body: this.newTask}).then((response) => {
                    this.project.tasks.push(response.data);
                    this.newTask = '';
                });
            }
        }
    }
</script>
