<template>
    <div>
        <div class="card-header">
            <span v-show="!isEdit">Create New Product </span>
            <span v-show="isEdit">Editing {{ name }}</span>
        </div>
        <div class="card-body">
            <div class="alert alert-warning" v-show="isEdit">
                You are currently editing {{ name }}
            </div>
        <form method="POST" @submit.prevent="isEdit ? update() : save()">
            <div class="form-group">
                <label>Product Name</label>
                <input v-model="data.product_name" required type="text" class="form-control" placeholder="Product name">
                <small v-if="errors.product_name" class="text-danger">{{ errors.product_name[0] }}</small>
            </div>
            <div class="form-group">
                <label>Quantity in stock</label>
                <input type="number" v-model="data.quantity" class="form-control" placeholder="Quantity">
                <small v-if="errors.quantity" class="text-danger">{{ errors.quantity[0] }}</small>
            </div>
            <div class="form-group">
                <label>Price Per Item</label>
                <input type="number" v-model="data.price" class="form-control" placeholder="Quantity">
                <small v-if="errors.price" class="text-danger">{{ errors.price[0] }}</small>
            </div>
            <button v-show="!isEdit" type="submit" class="btn btn-primary">Submit</button>
            <button v-show="isEdit" type="submit" class="btn btn-primary">Update</button>
        </form>
        <hr>
        <div class="table-responsive">

            <table class="table table-striped table-bordered table-condensed">
                <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity in stock</th>
                    <th>Price Per Item</th>
                    <th>Datetime submitted</th>
                    <th>Total Value</th>
                    <th>Edit</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item,index) in products">
                    <td>{{ item['product_name'] }}</td>
                    <td>{{ item['quantity'] }}</td>
                    <td>{{ item['price'] }}</td>
                    <td>{{ item['date_time'] }}</td>
                    <td>{{ item['quantity'] * item['price'] }}</td>
                    <td><button @click="editProduct(item,index)" type="button" class="btn btn-sm btn-success">Edit</button></td>
                </tr>
                </tbody>
            </table>

        </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['product'],
        data(){
            return {
                errors:{},
                id:'',
                name:'',
                isEdit:false,
                'products':this.product,
                data: {
                    product_name:'',
                    quantity:'',
                    price:'',
                    date_time:''
                }
            }
        },
        methods:{
            editProduct(data,id){
                this.data = data;
                this.isEdit = true;
                this.id = id;
            },

            update(){
                axios.put('/api/product/'+this.id,this.data)
                    .then((resp)=>{
                        this.success(this.name +' successfully updated');
                        this.isEdit = false;
                    })
                    .catch(error => {
                        this.loading = false;
                    });
            },
            save(){
                this.loading = true;
                this.errors = {},
                    axios.post('/api/product',this.data).then( (response) => {
                        this.loading = false;
                        this.products.push(response.data);
                        this.success(response.data.product_name+' successfully added to products list')
                        this.clearData();
                    }).catch(error=>{
                        this.errors = error.response.data.errors;
                        this.loading = false;
                    });
            },
            success(data){
                new Noty({
                    type: 'success',
                    layout: 'bottomRight',
                    theme: 'metroui',
                    text: data,
                    timeout: 10000,
                    progressBar: true,
                    closeWith: ['click', 'button'],
                    animation: {
                        open: 'noty_effects_open',
                        close: 'noty_effects_close'
                    },
                    id: false,
                    force: false,
                    killer: false,
                    queue: 'global',
                    container: false,

                    modal: false
                }).show();
            },
            clearData(){
                this.data.product_name = "";
                this.data.price = "";
                this.data.quantity = "";
            }
        }
    }
</script>
