<style>
    .widget-user-header {
        background-position: center center;
        background-size: cover;
    }
</style>

<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-4">

                <div class="card card-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header text-white" style="background-image:url('./img/vector.jpg')">
                        <h3 class="widget-user-username">{{form.name}}</h3>
                        <h5 class="widget-user-desc">{{form.bio}}</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" :src="getProfilePath()" alt="User Avatar">
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">3,200</h5>
                                    <span class="description-text">SALES</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">13,000</h5>
                                    <span class="description-text">FOLLOWERS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">35</h5>
                                    <span class="description-text">PRODUCTS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>

                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>
                            <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">

                            <!-- Activity -->
                            <div class="tab-pane" id="activity">
                                Your Activity
                            </div>
                            <!-- /.tab-pane -->

                            <!-- Setting -->
                            <div class="tab-pane active" id="settings">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-12 control-label">Name</label>

                                        <div class="col-sm-12">
                                            <input type="text" v-model="form.name" class="form-control" id="inputName" placeholder="Name" :class="{ 'is-invalid': form.errors.has('name') }">
                                            <has-error :form="form" field="name"></has-error>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail" class="col-sm-12 control-label">Email</label>

                                        <div class="col-sm-12">
                                            <input type="email" v-model="form.email" class="form-control" id="inputEmail" placeholder="Email" :class="{ 'is-invalid': form.errors.has('email') }">
                                            <has-error :form="form" field="email"></has-error>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputExperience" class="col-sm-12 control-label">Experience</label>

                                        <div class="col-sm-12">
                                            <textarea class="form-control" v-model="form.bio" id="inputExperience" placeholder="Bio" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                                            <has-error :form="form" field="bio"></has-error>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="photo" class="col-sm-12 control-label">Profile</label>
                                        <div class="col-sm-12">
                                            <input type="file" @change="encodePic" id="photo" name="photo" class="form-input">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="col-sm-12 control-label">Password (leave empty if not changing)</label>

                                        <div class="col-sm-12">
                                            <input type="password" v-model="form.password" class="form-control" id="password" placeholder="Password" :class="{ 'is-invalid': form.errors.has('password') }">
                                            <has-error :form="form" field="password"></has-error>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" @click.prevent="updateProfile" class="btn btn-success">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.tab-pane -->

                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
                
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: new Form({
                    id      : '',
                    name    : '',
                    email   : '',
                    password: '',
                    type    : '',
                    bio     : '',
                    photo   : ''
                })
            }
        },

        methods: {
            loadUsers() {
                axios.get("api/profile")
                    .then( ({ data }) => (this.form.fill(data)) )
            },

            encodePic(e) {
                let file = e.target.files[0]
                let reader = new FileReader()

                if(file['size'] < 2111775) {
                    reader.onloadend = (file) => {
                        // console.log(reader.result)
                        this.form.photo = reader.result
                    }
                    reader.readAsDataURL(file)

                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'Oobs...',
                        text: 'The max size allowed is 2mb'
                    })

                }

            },

            getProfilePath() {
                // return "/img/profile/" + this.form.photo

                let photo = "/img/profile/p.png"
                
                if (this.form.photo) {
                    if (this.form.photo.indexOf('base64') != -1) {
                        photo = this.form.photo
                    } else {
                        photo = "/img/profile/" + this.form.photo
                    }
                }

                return photo
            },

            updateProfile() {
                this.$Progress.start()
                
                this.form.put('api/profile')
                    .then(() => {
                        this.$Progress.finish()

                        Fire.$emit('AfterModify')
                    })
                    .catch(() => {
                        this.$Progress.fail()
                    })
            }
        },
        
        created() {
            this.loadUsers()

            Fire.$on( 'AfterModify', () => this.loadUsers() )
        }
    }
</script>
