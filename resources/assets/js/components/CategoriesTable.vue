<template>
    <div>
        <form @submit.prevent="createCategory">
            <div class="create-category-form input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Name</span>
                </div>
                <input v-model="newCategoryName" type="text" class="form-control" placeholder="Category Name" />

                <div class="input-group-prepend">
                    <span class="input-group-text">Parent Category</span>
                </div>
                <select class="form-control" v-model="newParentCatagory">
                    <option v-bind:value="null">None</option>
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
                <th>Parent Category</th>
                <th colspan="2"></th>
            </thead>
            <tbody>
                <tr v-for="row in categories" :key="row.id">
                    <td>{{ row.id }}</td>
                    <td>
                        <span v-if="edit != row.id">{{ row.name }}</span>
                        <span v-if="edit == row.id">
                            <input v-model="editCategoryName" type="text" class="form-control" placeholder="Category Name" />
                        </span>
                    </td>
                    <td>
                        <span v-if="edit != row.id">{{ row.parentCategoryName }}</span>
                        <span v-if="edit == row.id">
                            <select class="form-control" v-model="editParentCatagory">
                                <option v-bind:value="null">None</option>
                                <option v-for="category in categories" v-bind:key="category.id" v-bind:value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                        </span>
                    </td>
                    <td align="center"><button class="btn btn-danger btn-sm" @click.prevent="deleteCategory(row.id)"><i class="fa fa-times" /> Delete</button></td>
                    <td align="center">
                        <button v-if="edit != row.id" class="btn btn-primary btn-sm" @click.prevent="showEditCategory(row)"><i class="fa fa-plus" /> Edit</button>
                        <span v-if="edit == row.id">
                            <button class="btn btn-success btn-sm" @click.prevent="doEditCategory(row.id)"><i class="fa fa-plus" /> Save</button> 
                            <button class="btn btn-secondary btn-sm" @click.prevent="cancelEdit()"><i class="fa fa-plus" /> Cancel</button>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            categories: [],
            newCategoryName: '',
            newParentCatagory: null,
            cat_ids: [],
            edit: false,
            editCategoryName: '',
            editParentCatagory: null,
        };
    },
    mounted() {
        this.getCategories();
    },
    methods: {
        getCategories() {
            return axios.get('/api/categories')
                .then(response => {
                    this.categories = response.data.catagories;
                    this.cat_ids = response.data.cat_ids;
                }).catch(console.error);
        },
        createCategory() {
            return axios.post('/api/categories', 
                {
                    name: this.newCategoryName, 
                    parentCategory:this.newParentCatagory
                }
            ).then(this.getCategories)
                .then(() => this.newCategoryName = '', this.newParentCatagory = null)
                .catch(console.error);
        },
        deleteCategory(id) {
            return axios.post('/api/categories/' + id, {_method: 'DELETE'})
                .then(this.getCategories)
                .catch(console.error);
        },
        showEditCategory(row) {
            this.edit = row.id;
            this.editCategoryName = row.name;
            this.editParentCatagory = this.cat_ids.includes(row.parent_id) ? row.parent_id : null;
        },
        doEditCategory(id) {
            return axios.put('/api/categories/' + id, {
                    name: this.editCategoryName, 
                    parentCategory:this.editParentCatagory
                })
                .then(this.getCategories, this.edit = false)
                .catch(console.error);
        },
        cancelEdit() {
            this.edit = false;
        }
    }
}
</script>

<style>
.create-category-form {
    margin-bottom: 10px;
}
</style>