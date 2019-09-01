<template>
    <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="card mt-5">
              <div class="card-header">
                <h3 class="card-title">Users Management</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="showAddModal">Add User <i class="fas fa-user-plus"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Type</th>
                      <th>Registered At</th>
                      <th>Modify</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="user in users.data" :key="user.id">
                      <td>{{user.id}}</td>
                      <td>{{user.name}}</td>
                      <td>{{user.email}}</td>
                      <td>{{user.type | upFirstLetter}}</td>
                      <td>{{user.created_at | myDate}}</td>
                      <td>
                          <a href="#" @click="showEditModal(user)"><i class="fas fa-edit"></i></a> |
                            <a href="#" @click="deleteUser(user.id)">
                              <i class="fas fa-trash red"></i>
                            </a>
                      </td>
                    </tr>
                  </tbody>
                  <tfoot class="mt-5">
                    <pagination :data="users" :limit="2" :show-disabled="true" @pagination-change-page="getResults"></pagination>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>


        <div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="addUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

            <form @submit.prevent="editmode ? updateUser() : createUser()">
            <div class="modal-header">
                <h5 v-show="!editmode" class="modal-title" id="addUserLabel">Add New User</h5>
                <h5 v-show="editmode" class="modal-title" id="addUserLabel">Update user's info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input v-model="form.name" type="text" name="name"
                        placeholder="Name"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                    <has-error :form="form" field="name"></has-error>
                </div>

                <div class="form-group">
                    <input v-model="form.email" type="email" name="email"
                        placeholder="Email"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                    <has-error :form="form" field="email"></has-error>
                </div>

                <div class="form-group">
                    <textarea v-model="form.bio" type="text" name="bio"
                        placeholder="Enter short description about user"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                    <has-error :form="form" field="bio"></has-error>
                </div>

                <div class="form-group">
                    <select v-model="form.type" name="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                        <option value="">Select user role</option>
                        <option value="admin">Admin</option>
                        <option value="user">Standard User</option>
                        <option value="author">Author</option>
                    </select>
                    <has-error :form="form" field="type"></has-error>
                </div>

                <div class="form-group">
                    <input v-model="form.password" type="password" name="password"
                        placeholder="Password"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                    <has-error :form="form" field="password"></has-error>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
            </div>

            </form>
            </div>
        </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'users',
        data() {
            return {
                users: {},
                editmode: false,
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
            loadUsers: function() {
                axios.get('api/user').then( ({data}) => {
                    this.users = data;
                } );
            },
            createUser: function() {
                this.$Progress.start();

                this.form.post('api/user')
                .then(() => {
                    $('#addUser').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'User Created Successfully'
                    });
                    Fire.$emit('reload-users');
                });

                this.$Progress.finish();
            },
            updateUser(){
                this.$Progress.start();

                this.form.put('api/user/'+this.form.id)
                .then(() => {
                    $('#addUser').modal('hide');
                    swal.fire(
                        'Updated!',
                        'User has been updated successfully.',
                        'success'
                    );
                    Fire.$emit('reload-users');
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

                this.$Progress.finish();
            },
            deleteUser: function(user_id) {
                swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {

                        axios.delete('api/user/'+user_id)
                        .then( ({data}) => {
                            swal.fire(
                                'Deleted!',
                                'User has been deleted.',
                                'success'
                            );

                            Fire.$emit('reload-users');
                        });

                    }
                });
            },
            showAddModal: function() {
                this.form.reset();
                this.editmode = false;
                $('#addUser').modal('show');
            },
            showEditModal: function(user) {
                this.form.clear();
                this.editmode = true;
                $('#addUser').modal('show');
                this.form.fill(user);
            },
            getResults(page = 1) {
                axios.get('api/user?page='+page)
                .then( ({data}) => {
                    this.users = data;
                });
            },
        },
        created() {
            this.loadUsers();
            Fire.$on('reload-users',() => this.loadUsers());
        }
    }
</script>
