<template>
    <div style="width:70%">
        <div>
            <h1> {{ project.name }}</h1>
            <ul>
                <li v-for="task in project.tasks" v-text="task.body"></li>
            </ul>
            <input type="text" v-model="newTask" @blur="addTask" style="background-color: red" @keydown="tagPeers">
            <div v-if="activePeer" v-text="activePeer.name + 'is typing ....'"></div>
        </div>

        <div>
            <h4>
                Active Participants
            </h4>
            <ul>
                <li v-for="participant in participants" v-text="participant.name"></li>
            </ul>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                project: this.projectdata,
                newTask: "",
                activePeer: false,
                typingTimer: false,
                participants: [],
            };
        },
        computed: {
            channel() {
                return window.Echo.join('tasks.' + this.project.id);
            }
        },
        props: {
            projectdata: {
                required: true
            }
        },
        created() {
            this.channel
                .here(users => {
                    this.participants = users
                })
                .joining(users => {
                    this.participants.push(users);
                })
                .leaving(users => {
                    this.participants.splice(this.participants.indexOf(users), 1);
                })
                .listen('TaskCreated', e => {
                    this.project.tasks.push(e.task);
                    this.activePeer = false;
                }).listenForWhisper('typing', this.flashActivePeer);
        },

        methods: {
            flashActivePeer(e) {
                this.activePeer = e

                if (this.typingTimer) clearTimeout(this.typingTimer);

                this.typingTimer = setTimeout(() => {
                    this.activePeer = false;
                }, 3000);
            },

            addTask() {
                if (this.newTask) {
                    axios.post(`/api/projects/${this.project.id}/tasks`, {body: this.newTask}).then((response) => {
                        this.project.tasks.push(response.data);
                        this.newTask = '';
                    });
                }
            },

            tagPeers() {
                this.channel.whisper('typing', {
                    name: window.App.user.name,
                });

            },
        }
    }
</script>
