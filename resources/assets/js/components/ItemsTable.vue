<template>
    <div>
        <form @submit.prevent="createItem">
            <div class="create-item-form input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Name</span>
                </div>
                <input v-model="newItemName" type="text" class="form-control" placeholder="Item Name" /><br/>

                <div class="input-group-prepend">
                    <span class="input-group-text">Price</span>
                </div>
                <input v-model="newItemPrice" type="number" class="form-control" step=".01" placeholder="Item Price" /><br/>

                <div class="input-group-prepend">
                    <span class="input-group-text">Location</span>
                </div>
                <select class="form-control" v-model="newItemLocation">
                    <option v-bind:value="null">Select Location</option>
                    <option v-for="location in locations" v-bind:key="location.id" v-bind:value="location.id">
                        {{ location.name }}
                        </option>
                </select>

                <div class="input-group-prepend">
                    <span class="input-group-text">Category</span>
                </div>
                <select class="form-control" v-model="newItemCatagory">
                    <option v-bind:value="null">Select Category</option>
                    <option v-for="category in categories" v-bind:key="category.id" v-bind:value="category.id">
                        {{ category.name }}
                    </option>
                </select>

                <div class="input-group-append">
                    <button class="btn btn-primary">Create</button>
                </div>
            </div>
        </form>
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Location</th>
                <th>Category</th>
                <th colspan="2"></th>
            </thead>
            <tbody>
                <tr v-for="row in items" :key="row.id">
                    <td>{{ row.id }}</td>
                    <td>
                        <span v-if="edit != row.id">{{ row.name }}</span>
                        <span v-if="edit == row.id">
                            <input v-model="editItemName" type="text" class="form-control" placeholder="Item Name" />
                        </span>
                    </td>
                    <td>
                        <span v-if="edit != row.id">${{ row.price }}</span>
                        <span v-if="edit == row.id">
                            <input v-model="editItemPrice" type="text" class="form-control" placeholder="Item Price" />
                        </span>
                    </td>
                    <td>
                        <span v-if="edit != row.id">{{ row.locationName }}</span>
                        <span v-if="edit == row.id">
                            <select class="form-control" v-model="editItemLocation">
                                <option v-for="location in locations" v-bind:key="location.id" v-bind:value="location.id">
                                    {{ location.name }}
                                </option>
                            </select>
                        </span>
                    </td>
                    <td>
                        <span v-if="edit != row.id">{{ row.categoryName }}</span>
                        <span v-if="edit == row.id">
                            <select class="form-control" v-model="editItemCatagory">
                                <option v-for="category in categories" v-bind:key="category.id" v-bind:value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                        </span>
                    </td>
                    <td align="center"><button class="btn btn-danger btn-sm" @click.prevent="deleteItem(row.id)"><i class="fa fa-times" /> Delete</button></td>
                    <td align="center">
                        <button v-if="edit != row.id" class="btn btn-primary btn-sm" @click.prevent="showEditItem(row)"><i class="fa fa-plus" /> Edit</button>
                        <span v-if="edit == row.id">
                            <button class="btn btn-success btn-sm" @click.prevent="doEditItem(row.id)"><i class="fa fa-plus" /> Save</button> 
                            <button class="btn btn-secondary btn-sm" @click.prevent="cancelEdit()"><i class="fa fa-plus" /> Cancel</button>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="text-center" style="margin-bottom: 10px;" id="paginator">
            <a href="#" v-on:click="loadMore()">Load More Results</a>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            items: [],
            categories: [],
            locations: [],
            newItemName: '',
            newItemPrice: '',
            newItemLocation: null,
            newItemCatagory: null,
            edit: false,
            editItemName: '',
            editItemPrice: '',
            editItemLocation: '',
            editItemCatagory: '',
            paginate: 1
        };
    },
    mounted() {
        this.getItems();
    },
    methods: {
        getItems() {
            if (this.edit) {
                this.paginate = 1;
            }

            return axios.get('/api/items', {
                params: {
                    'paginate' : this.paginate
                }
            })
                .then(response => {
                    if (this.edit || !this.items.length) {
                        this.items = response.data.items;
                        this.edit = false;
                    }else {
                        this.items = this.items.concat(response.data.items);
                    }

                    if (!this.categories.length && response.data.categories.length) {
                        this.categories = response.data.categories;
                    }

                    if (!this.locations.length && response.data.locations) {
                        this.locations = response.data.locations;
                    }
                }).catch(console.error);
        },
        createItem() {
            return axios.post('/api/items', 
                {
                    name: this.newItemName, 
                    price: this.newItemPrice,
                    location: this.newItemLocation,
                    category:this.newItemCatagory
                }
            ).then(() => 
                this.newItemName = '', 
                this.newItemPrice = '', 
                this.newItemLocation = null, 
                this.newItemCatagory = null,
                this.paginate = 1,
                this.items = [],
                this.getItems()
            ).catch(console.error);
        },
        deleteItem(id) {
            return axios.post('/api/items/' + id, {_method: 'DELETE'})
                .then(this.paginate = 1, this.items = [], this.getItems())
                .catch(console.error);
        },
        showEditItem(row) {
            this.edit = row.id;
            this.editItemPrice = row.price;
            this.editItemName = row.name;
            this.editItemCatagory = row.category_id;
            this.editItemLocation = row.location_id;
        },
        doEditItem(id) {
            return axios.put('/api/items/' + id, {
                    name: this.editItemName, 
                    price: this.editItemPrice,
                    location: this.editItemLocation,
                    category:this.editItemCatagory,
                })
                .then(this.getItems())
                .catch(console.error);
        },
        loadMore() {
            ++this.paginate;
            this.getItems();
        },
        cancelEdit() {
            this.edit = false;
        }
    }
}
</script>

<style>
.create-item-form {
    margin-bottom: 10px;
}
</style>