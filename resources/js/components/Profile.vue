<template>
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-12">
                <div class="card card-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header text-white">
                    <h3 class="widget-user-username">Elizabeth Pierce</h3>
                    <h5 class="widget-user-desc">Web Designer</h5>
                </div>
                <div class="widget-user-image">
                    <img class="img-circle" :src="getProfileImage" alt="User Avatar" v-if="this.form.photo">
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
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Activity</a></li>
                        <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="activity">
                                <p>User's Activity</p>
                            </div>

                        <div class="tab-pane" id="settings">
                            <form @submit.prevent="updateProfile" class="form-horizontal">
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Name</label>

                                <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputName" v-model="form.name" placeholder="Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                                <div class="col-sm-10">
                                <input type="email" class="form-control" id="inputEmail" v-model="form.email" placeholder="Email">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputBio" class="col-sm-2 control-label">Bio</label>

                                <div class="col-sm-10">
                                <textarea class="form-control" v-model="form.bio" id="inputBio" placeholder="Write about yourself"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="profilePhoto" class="col-sm-12 control-label">Profile Photo</label>

                                <div class="row">
                                    <div class="col-sm-8 center-items">
                                        <input type="file" @change="updateImage" class="form-control" id="profilePhoto">
                                    </div>
                                    <div class="col-sm-2" v-if="this.form.photo">
                                        <img class="img-circle img-fluid" :src="getProfileImage" alt="User Avatar" v-if="this.form.photo">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword" class="col-sm-12 control-label">Password (Leave empty if not changing)</label>

                                <div class="col-sm-10">
                                <input type="password" v-model="form.password" class="form-control" id="inputPassword" placeholder="Password">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-success">Update</button>
                                </div>
                            </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

.widget-user-header {

    background: url('/img/user-profile-bg.jpg') center center;
    background-size: cover;
    height:250px;

}

.center-items {
    display: flex;
    align-items: center;
    justify-content: center;
}


</style>

<script>
    export default {
        data() {
            return {
                photoEdited: false,
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: ''
                })
            }
        },
        methods: {
            updateImage: function(e) {
                let file = e.target.files[0];

                if(file.size < 2*1024*1024) { // 2MB
                    let reader = new FileReader();
                    reader.onloadend = (file) => {
                        this.form.photo = reader.result;
                        this.photoEdited = true;
                    }
                    reader.readAsDataURL(file);
                } else {
                    swal.fire(
                        'Error!',
                        'File size is bigger than 2MB',
                        'error'
                    );
                }

            },
            updateProfile: function() {
                this.$Progress.start();
                this.form.put('api/profile')
                .then( ({data}) => {

                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            axios.get('api/profile')
            .then(({data}) => {
                this.form.fill(data);
            });
        },
        computed: {
            getProfileImage: function() {
                if(this.photoEdited)
                    return this.form.photo;
                return 'img/profile/'+this.form.photo;
            }
        }
    }
</script>
