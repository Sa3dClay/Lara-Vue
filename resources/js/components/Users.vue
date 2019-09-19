<template>
    <div class="container">

        <div class="row justify-content-center" v-if="$gate.isAdminOrAuthor()">
            <div class="col-md-12">

                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Users</h4>
                        
                        <div class="card-tools">
                            <!-- data-toggle="modal" data-target="#addNew" -->
                            <button class="btn btn-success" @click="newModal"><i class="fas fa-user-plus"></i> Add New</button>
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
                                <tr v-for="user in users.data" :key="user.id">
                                    <th scope="row">{{ user.id }}</th>
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.type | upChar }}</td>
                                    <td>{{ user.created_at }}</td>
                                    <td>
                                        <a href="#" @click="editModal(user)">
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

                    <div class="card-footer">
                        <pagination :data="users" @pagination-change-page="getUsers">
                            <span slot="prev-nav"><i class="fas fa-chevron-left"></i></span>
	                        <span slot="next-nav"><i class="fas fa-chevron-right"></i></span>
                        </pagination>
                    </div>
                </div><!-- end of card -->

                <!-- Modal -->
                <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add User</h5>
                                <h5 v-show="editMode" class="modal-title" id="addNewLabel">Edit User</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form @submit.prevent="editMode ? updateUser() : createUser()">
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
                                    <!-- Update & Create -->
                                    <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
                                    <button v-show="editMode"  type="submit" class="btn btn-success">Update</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div><!-- end of modal -->

            </div>
        </div>

        <div v-if="!$gate.isAdminOrAuthor()">
            <not-found></not-found>
        </div>

    </div>
</template>

<script>
import { setInterval } from 'timers';
    export default {
        data() {
            return {
                editMode: false,

                form: new Form({
                    id      : '',
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
            newModal() {
                this.editMode = false
                // this.form.clear()
                this.form.reset()

                $('#addNew').modal('show')
            },

            editModal(user) {
                this.editMode = true

                this.form.reset()
                $('#addNew').modal('show')

                this.form.fill(user)
            },

            loadUsers() {
                if( this.$gate.isAdminOrAuthor() ) {
                    axios.get("api/user")
                        .then( ({ data }) => (this.users = data) )
                }
            },

            getUsers(page = 1) {
                axios.get('api/user?page=' + page)
                    .then(response => {
                        this.users = response.data
                    })
            },

            updateUser() {
                // console.log('Editing')
                this.$Progress.start()

                this.form.put('api/user/'+this.form.id)
                    .then(() => {
                        $('#addNew').modal('hide')

                        this.$Progress.finish()

                        Toast.fire({
                            type: 'success',
                            title: 'User updated successfully'
                        })

                        // Call event
                        Fire.$emit('AfterModify')
                    })
                    .catch(() => {
                        this.$Progress.fail()
                    })
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

                        // Call event
                        Fire.$emit('AfterModify')
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

            // Create event
            Fire.$on( 'AfterModify', () => this.loadUsers() )

            Fire.$on('searching', () => {
                let query = this.$parent.search

                axios.get('api/findUser?q=' + query)
                    .then((data) => {
                        this.users = data.data
                    })
                    .catch(() => {
                        Toast.fire({
                            type: 'error',
                            title: 'Can not be found'
                        })
                    })
            })

            // Call this function every 4 seconds
            // setInterval( () => this.loadUsers(), 4000 )
        }
    }
</script>
