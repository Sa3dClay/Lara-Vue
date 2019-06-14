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
                                    <th scope="col">Modify</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>Mark</td>
                                    <td>mark@lara.com</td>
                                    <td>Customer</td>
                                    <td>
                                        <a href="#">
                                            <i class="fa fa-edit text-green"></i>
                                        </a>
                                        |
                                        <a href="#">
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

                            <div class="modal-body">
                                Inputs
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Create</button>
                            </div>

                        </div>
                    </div>
                </div><!-- end of modal -->

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
