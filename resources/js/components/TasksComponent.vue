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
            <div class="col-sm-4" v-for="status in statuses">
                <div class="card mb-3">
                    <h5 class="card-header" :class="'border-'+status.color">{{ status.title }}</h5>
                    <div class="list-group list-group-flush">
                        <button type="button" class="list-group-item list-group-item-action" :class="{ 'list-group-item-primary': task.id === editingTask.id }" @click="editModalShow(task)" v-for="task in tasks" v-if="task.status_id === status.id">
                            {{ task.title }}
                            <p class="card-text"><small class="text-muted">Comments: {{ task.comments.length }}</small></p>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <b-modal id="editTask" title="Editing task" @hide="resetEditingTask" hide-footer>
            <div class="py-3">
                <h6>Title:</h6>
                <p>{{ editingTask.title }}</p>
                <h6>Description:</h6>
                <p>{{ editingTask.description }}</p>
                <div class="form-check form-check-inline" v-for="status in statuses">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" :id="'status'+status.id" :value="status.id" v-model="editingTask.status_id">
                    <label class="form-check-label" :for="'status'+status.id">{{ status.title }}</label>
                </div>
                <button type="button" class="btn btn-primary" @click="editTask" :disabled="editingTask.status_id === editingTaskNotDirtyStatus">Save</button>
            </div>
            <div class="py-3">
                <div class="form-group">
                    <label for="newComment"><h6>Add comment:</h6></label>
                    <textarea class="form-control" id="newComment" v-model="newComment.text" rows="3"></textarea>
                </div>
                <button type="button" class="btn btn-primary" @click="createComment" :disabled="!newComment.text.length">Add</button>
            </div>
            <div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item" v-for="comment in editingTask.comments">
                        <p class="mb-1 text-wrap">{{ comment.text }}</p>
                        <div class="d-flex w-100 justify-content-between">
                            <small class="text-muted">{{ comment.user.name }}</small>
                            <small class="text-muted">{{ comment.created_at }}</small>
                        </div>
                    </div>
                </div>
<!--                <blockquote class="blockquote" v-for="comment in editingTask.comments">-->
<!--                    <p class="mb-0">{{ comment.text }}</p>-->
<!--                    <footer class="blockquote-footer">{{ comment.user.name }}</footer>-->
<!--                </blockquote>-->
            </div>
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
                editingTaskNotDirtyStatus: null,
                newComment: {
                    text: ''
                },
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
            resetEditingTask() {
                this.editingTask.title = this.editingTask.description = '';
                this.editingTask.status_id = this.editingTask.id = null;
            },
            editModalShow(task) {
                this.editingTask = Object.assign({}, task);
                this.editingTaskNotDirtyStatus = this.editingTask.status_id;
                this.$bvModal.show('editTask');
            },
            editTask() {
                axios.put('api/tasks/'+this.editingTask.id, {
                    task: this.editingTask
                })
                    .then(response => {
                        this.tasks.splice(this.tasks.findIndex((item) => {return item.id === response.data.id}), 1, response.data);
                        this.editingTaskNotDirtyStatus = response.data.status_id;
                    })
                    .catch(error=>{
                        console.log(error)
                    });
            },
            createComment() {
                axios.post('api/comments', {
                    comment: {
                        text: this.newComment.text,
                        user_id: this.auth,
                        task_id: this.editingTask.id
                    }
                })
                    .then(response => {
                        this.editingTask.comments.unshift(response.data);
                        this.newComment.text = '';
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
