<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <h5 class="card-header">To do</h5>
                    <ul class="list-group list-group-flush">
                        <li v-for="task in tasks" v-if="task.status_id === 1" class="list-group-item">{{ task.title }}</li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <h5 class="card-header">Doing</h5>
                    <ul class="list-group list-group-flush">
                        <li v-for="task in tasks" v-if="task.status_id === 2" class="list-group-item">{{ task.title }}</li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <h5 class="card-header">Done</h5>
                    <ul class="list-group list-group-flush">
                        <li v-for="task in tasks" v-if="task.status_id === 3" class="list-group-item">{{ task.title }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TasksComponent",
        data() {
            return {
                tasks: [],
            }
        },
        mounted() {
            this.loadTasks();
        },
        methods: {
            loadTasks() {
                axios.get('api/tasks')
                    .then(response => {
                        this.tasks = response.data;
                    })
                    .catch(error=>{
                        console.log(error)
                    });
            },
        }
    }
</script>

<style scoped>

</style>
