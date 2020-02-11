<template>
    <div class="container">
        <div class="top-panel mb-3">
            <button type="button" class="btn btn-primary" v-b-modal.addTask>
                Add task
            </button>

            <b-modal id="addTask" title="Adding task">
                <div class="form-group">
                    <label for="newTaskTitleInput">Title</label>
                    <input type="text" class="form-control" id="newTaskTitleInput" v-model="newTask.title">
                </div>
                <div class="form-group">
                    <label for="newTaskDescriptionInput">Description</label>
                    <textarea class="form-control" id="newTaskDescriptionInput" rows="3" v-model="newTask.description"></textarea>
                </div>
                <div class="form-check form-check-inline" v-for="status in statuses">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" :id="'status'+status.id" :value="status.id" v-model="newTask.status_id">
                    <label class="form-check-label" :for="'status'+status.id">{{ status.title }}</label>
                </div>
                <template v-slot:modal-footer="{ cancel, ok }">
                    <button type="button" class="btn btn-secondary" @click="cancel()" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="createTask" :disabled="!isNewTaskValid">Add</button>
                </template>
            </b-modal>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <h5 class="card-header">To do</h5>
                    <div class="list-group list-group-flush">
                        <a href="#" @click="editModalShow(task)" v-for="task in tasks" v-if="task.status_id === 1" class="list-group-item list-group-item-action">
                            {{ task.title }}
                            <p class="card-text"><small class="text-muted">Comments: {{ task.comments.length }}</small></p>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <h5 class="card-header">Doing</h5>
                    <ul class="list-group list-group-flush">
                        <li v-for="task in tasks" v-if="task.status_id === 2" class="list-group-item">
                            {{ task.title }}
                            <p class="card-text"><small class="text-muted">Comments: {{ task.comments.length }}</small></p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <h5 class="card-header">Done</h5>
                    <ul class="list-group list-group-flush">
                        <li v-for="task in tasks" v-if="task.status_id === 3" class="list-group-item">
                            {{ task.title }}
                            <p class="card-text"><small class="text-muted">Comments: {{ task.comments.length }}</small></p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <b-modal id="editTask" title="Editing task">
            <div class="form-check form-check-inline" v-for="status in statuses">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" :id="'status'+status.id" :value="status.id" v-model="editingTask.status_id">
                <label class="form-check-label" :for="'status'+status.id">{{ status.title }}</label>
            </div>
            <template v-slot:modal-footer="{ cancel, ok }">
                <button type="button" class="btn btn-secondary" @click="cancel()" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="editTask">Save</button>
            </template>
        </b-modal>
    </div>
</template>

<script>
    export default {
        name: "TasksComponent",
        props: ['auth'],
        data() {
            return {
                statuses: [],
                tasks: [],
                newTask: {
                    title: '',
                    description: '',
                    status_id: null,
                    user_id: this.auth
                },
                editingTask: {},
            }
        },
        computed: {
            isNewTaskValid() {
                return this.newTask.title.length && this.newTask.description.length && this.newTask.status_id
            }
        },
        mounted() {
            this.loadTasks();
            this.loadTaskStatuses();
        },
        methods: {
            loadTaskStatuses() {
                axios.get('api/task_statuses')
                    .then(response => {
                        this.statuses = response.data;
                    })
                    .catch(error=>{
                        console.log(error)
                    });
            },
            loadTasks() {
                axios.get('api/tasks')
                    .then(response => {
                        this.tasks = response.data;
                    })
                    .catch(error=>{
                        console.log(error)
                    });
            },
            createTask() {
                axios.post('api/tasks', {
                    task: this.newTask
                })
                    .then(response => {
                        this.tasks.push(response.data);
                        this.$bvModal.hide('addTask');
                        this.resetNewTask();
                    })
                    .catch(error=>{
                        console.log(error)
                    });
            },
            resetNewTask() {
                this.newTask.title = this.newTask.description = '';
                this.newTask.status_id = null;
            },
            editModalShow(task) {
                this.editingTask = Object.assign({}, task);
                this.$bvModal.show('editTask');
            },
            editTask() {
                axios.put('api/tasks/'+this.editingTask.id, {
                    task: this.editingTask
                })
                    .then(response => {
                        this.tasks.splice(this.tasks.findIndex((item) => {return item.id === response.data.id}), 1, response.data);
                        this.$bvModal.hide('editTask');
                    })
                    .catch(error=>{
                        console.log(error)
                    });
            }
        }
    }
</script>

<style scoped>

</style>
