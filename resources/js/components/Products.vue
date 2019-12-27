<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <!-- start of card -->
                <div class="card">

                    <!-- start of card-header -->
                    <div class="card-header">
                        <h4>Products</h4>

                        <div class="card-tools" v-if="$gate.isAdminOrAuthor()">
                            <button class="btn btn-success" @click="newModal"><i class="fab fa-product-hunt"></i> Add New</button>
                        </div>
                    </div><!-- end of card-header -->

                    <!-- start of card-body -->
                    <div class="card-body">
                        
                        <div class="products">
                            <div class="row">

                                <div class="col-md-4 single-product" v-for="pro in products.data" :key="pro.id">
                                    <div class="cont">
                                        <img class="img-fluid" :src="getProImgPath(pro.product_pic)" alt="Product Photo">

                                        <div class="overlay">
                                            <div class="text">
                                                <p>{{ pro.name }}</p>
                                                <p>{{ pro.brand }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <hr style="margin: 4px 0">
                                    <p class="price">${{ pro.price }}</p>
                                    
                                    <!-- Update and delete -->
                                    <div class="row upd" v-if="$gate.isAdminOrAuthor()">
                                        <div class="col col-sm-6">
                                            <a href="#" @click="editModal(pro)">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                        </div>

                                        <div class="col col-sm-6">
                                            <a href="#" @click="deleteProduct(pro.id)">
                                                <i class="fa fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </div><!-- End of update -->

                                </div>

                            </div>
                        </div>

                    </div><!-- end of card-body -->

                    <!-- start of card-footer -->
                    <div class="card-footer">
                        <pagination :data="products" @pagination-change-page="getProducts">
                            <span slot="prev-nav"><i class="fas fa-chevron-left"></i></span>
	                        <span slot="next-nav"><i class="fas fa-chevron-right"></i></span>
                        </pagination>
                    </div><!-- end of card-footer -->

                </div><!-- end of card -->

                <!-- Modal -->
                <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add Product</h5>
                                <h5 v-show="editMode" class="modal-title" id="addNewLabel">Edit Product</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form @submit.prevent="editMode ? updateProduct() : createProduct()">
                                <div class="modal-body">
                                    
                                    <div class="form-group">
                                        <input v-model="form.name" type="text" name="name" id="name" placeholder="Name"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                        <has-error :form="form" field="name"></has-error>
                                    </div>

                                    <div class="form-group">
                                        <input v-model="form.brand" type="text" name="brand" id="brand" placeholder="Brand"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('brand') }">
                                        <has-error :form="form" field="brand"></has-error>
                                    </div>

                                    <div class="form-group">
                                        <input v-model="form.quantity" type="number" name="quantity" id="quantity" placeholder="Quantity"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('quantity') }">
                                        <has-error :form="form" field="quantity"></has-error>
                                    </div>

                                    <div class="form-group">
                                        <input v-model="form.price" type="number" name="price" id="price" placeholder="Price"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('price') }">
                                        <has-error :form="form" field="price"></has-error>
                                    </div>

                                    <div class="form-group">
                                        <select v-model="form.category" name="category" id="category" class="form-control" 
                                            :class="{ 'is-invalid': form.errors.has('category') }">
                                            <option value="">Select Product Category</option>
                                            <option value="default">Others</option>
                                            <option value="electronics">Electronics</option>
                                            <option value="accessories">Accessories</option>
                                        </select>
                                        <has-error :form="form" field="category"></has-error>
                                    </div>

                                    <div class="form-group">
                                        <input type="file" id="product_pic" 
                                            name="product_pic" @change="encodePic" class="form-control" 
                                            :class="{ 'is-invalid': form.errors.has('product_pic') }">
                                        <has-error :form="form" field="product_pic"></has-error>
                                    </div>

                                    <div class="form-group">
                                        <textarea v-model="form.descreption" name="descreption" id="descreption"
                                        placeholder="Product Descreption (Optional)"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('descreption') }"></textarea>
                                        <has-error :form="form" field="descreption"></has-error>
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
    </div>
</template>

<script>
    export default {
        data() {
            return {
                editMode: false,

                form: new Form({
                    id          : '',
                    name        : '',
                    brand       : '',
                    quantity    : '',
                    price       : '',
                    category    : '',
                    product_pic : '',
                    descreption : '',
                    visible     : ''
                }),

                products: {}
            }
        },

        methods: {
            newModal() {
                this.editMode = false
                
                this.form.reset()

                $('#addNew').modal('show')
            },

            editModal(product) {
                this.editMode = true

                this.form.reset()
                $('#addNew').modal('show')

                this.form.fill(product)
            },

            loadProducts() {
                axios.get("api/product")
                    .then( ({ data }) => (this.products = data) )
            },

            getProducts(page = 1) {
                axios.get('api/product?page=' + page)
                    .then(response => {
                        this.products = response.data
                    })
            },

            createProduct() {
                this.$Progress.start()

                this.form.post('api/product')
                    .then(() => {
                        $('#addNew').modal('hide')

                        this.$Progress.finish()

                        Toast.fire({
                            type: 'success',
                            title: 'Product created successfully'
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

            encodePic(e) {
                let file = e.target.files[0]
                let reader = new FileReader()

                if(file['size'] <= 2048000) {
                    reader.onloadend = (file) => {
                        this.form.product_pic = reader.result
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

            updateProduct() {
                this.$Progress.start()

                this.form.put('api/product/'+this.form.id)
                    .then(() => {
                        $('#addNew').modal('hide')

                        this.$Progress.finish()

                        Toast.fire({
                            type: 'success',
                            title: 'Product updated successfully'
                        })

                        // Call event
                        Fire.$emit('AfterModify')
                    })
                    .catch(() => {
                        this.$Progress.fail()
                    })
            },

            deleteProduct(id) {
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

                        this.form.delete('api/product/'+id)
                            .then(() => {
                                Swal.fire(
                                    'Deleted!',
                                    'Product has been deleted.',
                                    'success'
                                )

                                Fire.$emit('AfterModify')
                            })
                            .catch(() => {
                                Swal.fire(
                                    'Oobs...',
                                    'Product hasn not been deleted!',
                                    'error'
                                )
                            })
                    }
                })
            },

            getProImgPath(pic) {
                // console.log(pic)
                let product_pic = "/img/products/" + pic                
                return product_pic
            },
        },

        created() {
            this.$Progress.start()
            this.loadProducts()
            this.$Progress.finish()

            // Create event
            Fire.$on( 'AfterModify', () => this.loadProducts() )
        }
    }
</script>
