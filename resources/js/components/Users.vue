<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Users</h4>
                        
                        <div class="card-tools">
                            <button class="btn btn-success" data-toggle="modal" data-target="#addNew"><i class="fas fa-user-plus"></i> Add New</button>
                        </div>
                    </div>

                    <div class="card-body">

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Registered At</th>
                                    <th scope="col">Modify</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="user in users" :key="user.id">
                                    <th scope="row">{{ user.id }}</th>
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.type | upChar }}</td>
                                    <td>{{ user.created_at }}</td>
                                    <td>
                                        <a href="#">
                                            <i class="fa fa-edit text-green"></i>
                                        </a>
                                        |
                                        <a href="#" @click="deleteUser(user.id)">
                                            <i class="fa fa-trash text-red"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table><!-- end of the table -->

                    </div>
                </div><!-- end of card -->

                <!-- Modal -->
                <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title" id="addNewLabel">User Info</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form @submit.prevent="createUser">
                                <div class="modal-body">
                                    
                                    <div class="form-group">
                                        <input v-model="form.name" type="text" name="name" placeholder="Name"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                        <has-error :form="form" field="name"></has-error>
                                    </div>

                                    <div class="form-group">
                                        <input v-model="form.email" type="email" name="email" placeholder="Email"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                        <has-error :form="form" field="email"></has-error>
                                    </div>

                                    <div class="form-group">
                                        <textarea v-model="form.bio" name="bio" id="bio"
                                        placeholder="Short bio for user (Optional)"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                                        <has-error :form="form" field="bio"></has-error>
                                    </div>

                                    <div class="form-group">
                                        <select name="type" v-model="form.type" id="type" class="form-control" 
                                            :class="{ 'is-invalid': form.errors.has('type') }">
                                            <option value="">Select User Role</option>
                                            <option value="admin">Admin</option>
                                            <option value="user">Standard User</option>
                                            <option value="author">Author</option>
                                        </select>
                                        <has-error :form="form" field="type"></has-error>
                                    </div>

                                    <div class="form-group">
                                        <input v-model="form.password" type="password" name="password" id="password"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                        <has-error :form="form" field="password"></has-error>
                                    </div>

                                </div><!-- end of modal body -->

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div><!-- end of modal -->

            </div>
        </div>
    </div>
</template>

<script>
import { setInterval } from 'timers';
    export default {
        data() {
            return {
                form: new Form({
                    name    : '',
                    email   : '',
                    password: '',
                    type    : '',
                    bio     : '',
                    photo   : ''
                }),

                users: {}
            }
        },
        methods: {
            loadUsers() {
                axios.get("api/user").then( ({ data }) => (this.users = data.data) )
            },

            createUser() {
                this.$Progress.start()

                this.form.post('api/user')
                    .then(() => {
                        $('#addNew').modal('hide')

                        this.$Progress.finish()

                        Toast.fire({
                            type: 'success',
                            title: 'User created successfully'
                        })

                        // Create event
                        Fire.$emit('AfterModify')

                        // this.loadUsers()
                    })
                    .catch(() => {
                        this.$Progress.fail()

                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    })
            },

            deleteUser(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {

                        // Send request to the server
                        this.form.delete('api/user/'+id)
                            .then(() => {
                                Swal.fire(
                                    'Deleted!',
                                    'User has been deleted.',
                                    'success'
                                )

                                Fire.$emit('AfterModify')
                            })
                            .catch(() => {
                                Swal.fire(
                                    'Oobs...',
                                    'User hasn not been deleted!',
                                    'error'
                                )
                            })
                    }
                })
            }
        },
        created() {
            this.$Progress.start()
            this.loadUsers()
            this.$Progress.finish()

            // Call event
            Fire.$on( 'AfterModify', () => this.loadUsers() )

            // Call this function every 4 seconds
            // setInterval( () => this.loadUsers(), 4000 )
        }
    }
</script>
