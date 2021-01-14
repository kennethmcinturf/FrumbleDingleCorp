<template>
    <div>
        <form @submit.prevent="createLocation">
            <div class="create-location-form input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Name</span>
                </div>
                <input v-model="newLocationName" type="text" class="form-control" placeholder="Location Name" />
                <div class="input-group-append">
                    <button class="btn btn-primary">Create</button>
                </div>
            </div>
        </form>
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <th>ID</th>
                <th>Name</th>
                <th colspan="2"></th>
            </thead>
            <tbody>
                <tr v-for="row in locations" :key="row.id">
                    <td>{{ row.id }}</td>
                    <td>
                        <span v-if="edit != row.id">{{ row.name }}</span>
                        <span v-if="edit == row.id">
                            <input v-model="editLocationName" type="text" class="form-control" placeholder="Location Name" />
                        </span>
                    </td>
                    <td align="center">
                        <button class="btn btn-danger btn-sm" @click.prevent="deleteLocation(row.id)"><i class="fa fa-times" /> Delete</button>
                    </td>
                    <td align="center">
                        <button v-if="edit != row.id" class="btn btn-primary btn-sm" @click.prevent="showLocationEdit(row)"><i class="fa fa-plus" /> Edit</button>
                        <span v-if="edit == row.id">
                            <button v-if="edit == row.id" class="btn btn-success btn-sm" @click.prevent="doEditLocation(row.id)">
                                <i class="fa fa-plus" /> Save
                            </button>
                            <button class="btn btn-secondary btn-sm" @click.prevent="cancelEdit()">
                                <i class="fa fa-plus" /> Cancel
                            </button>
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
            locations: [],
            newLocationName: '',
            editLocationName: '',
            edit: false
        };
    },
    mounted() {
        this.getLocations();
    },
    methods: {
        getLocations() {
            return axios.get('/api/locations')
                .then(response => {
                    this.locations = response.data;
                }).catch(console.error);
        },
        createLocation() {
            return axios.post('/api/locations', {name: this.newLocationName})
                .then(this.getLocations)
                .then(() => this.newLocationName = '')
                .catch(console.error);
        },
        deleteLocation(id) {
            return axios.post('/api/locations/' + id, {_method: 'DELETE'})
                .then(this.getLocations)
                .catch(console.error);
        },
        showLocationEdit(row) {
            this.edit = row.id;
            this.editLocationName = row.name;
        },
        doEditLocation(id) {
            return axios.put('/api/locations/' + id, {name: this.editLocationName})
                .then(this.getLocations, this.edit = false)
                .catch(this.getLocations, console.error);
        },
        cancelEdit() {
            this.edit = false;
        }
    }
}
</script>

<style>
.create-location-form {
    margin-bottom: 10px;
}
</style>