<template>
    <div>
        <form @submit.prevent="getResults">
            <div class="input-group">
                <input v-model="reportPrice" type="number" class="form-control" step=".01" 
                    placeholder="Enter price to filter generate new report" />

                <div class="input-group-append">
                    <button class="btn btn-primary">Generate New Report</button>
                </div>
            </div>
        </form>
        <div v-if="results.length < 1 && items.length < 1" class="text-center" style="margin-top:40px">
            <h4>No data to display, try adjusting filters</h4>
        </div>
        <span v-if="items.length > 0">
            <h6 class="text-left" style="margin-top:10px;">
                <span v-if="!prop || prop == 'locations'">| Location: {{ items[0].locationName }} </span>
                <span v-if="!prop || prop == 'category'">| Category: {{ items[0].categoryName }}</span>
                <span v-if="items[0].category.parentCategoryName && !prop">
                    | Parent Category: {{ items[0].category.parentCategoryName }}
                </span>
                <button class="btn btn-success btn-sm pull-right" @click.prevent="clearItemsReport()">Back To Report</button>
            </h6>
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <th>Name</th>
                    <th>Price</th>
                </thead>
                <tbody>
                    <tr v-for="row in items" :key="row.id">
                        <td>{{ row.name }}</td>
                        <td>${{ row.price }}</td>
                    </tr>
                </tbody>
            </table>
        </span>
        <table class="table table-striped table-bordered" v-if="results.length > 0 && items.length < 1">
            <thead class="thead-dark">
                <th>
                    Location
                    <a style="text-decoration:none;" href="#" v-on:click="sort('location_name')">
                        <span class="fa-stack pull-right">
                            <span class="fa fa-sort-up fa-stack-1x"></span>
                            <span class="fa fa-sort-down fa-stack-1x"></span>
                        </span>
                    </a>
                </th>
                <th>
                    Parent Category
                    <a style="text-decoration:none;" href="#" v-on:click="sort('parent_category_name')">
                        <span class="fa-stack pull-right">
                            <span class="fa fa-sort-up fa-stack-1x"></span>
                            <span class="fa fa-sort-down fa-stack-1x"></span>
                        </span>
                    </a>
                </th>
                <th>
                    Category
                    <a style="text-decoration:none;" href="#" v-on:click="sort('category_name')">
                        <span class="fa-stack pull-right">
                            <span class="fa fa-sort-up fa-stack-1x"></span>
                            <span class="fa fa-sort-down fa-stack-1x"></span>
                        </span>
                    </a>
                </th>
                <th>
                    Price at or Above Input
                    <a style="text-decoration:none;" href="#" v-on:click="sort('count')">
                        <span class="fa-stack pull-right">
                            <span class="fa fa-sort-up fa-stack-1x"></span>
                            <span class="fa fa-sort-down fa-stack-1x"></span>
                        </span>
                    </a>
                </th>
            </thead>
            <tbody>
                <tr v-for="(row, index) in results" :key="index">
                    <td>
                        <span v-if="row.count < 1">{{ row.location_name }}</span>
                        <a style="text-decoration:none;" href="#" v-on:click="getItems(row, 'locations')" v-if="row.count > 0">
                            {{ row.location_name }}
                        </a>
                    </td>
                    <td>{{ row.parent_category_name }}</td>
                    <td>
                        <span v-if="row.count < 1">{{ row.category_name }}</span>
                        <a style="text-decoration:none;" href="#" v-on:click="getItems(row, 'category')" v-if="row.count > 0">
                            {{ row.category_name }}
                        </a>
                    </td>
                    <td>
                        <span v-if="row.count < 1">{{ row.count }}</span>
                        <span v-if="row.count > 0">
                            <a style="text-decoration:none;" href="#" v-on:click="getItems(row)">
                                {{ row.count }}
                            </a>
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
            results: [],
            reportPrice: '',
            reverse: false,
            items : [],
            prop: null
        };
    },
    methods: {
        getResults() {
            if (!this.reportPrice || isNaN(this.reportPrice)) {
                return;
            }

            this.items = [];
            this.prop = null;
            return axios.get('/api/price-product-report/' + this.reportPrice)
                .then(response => {
                    this.results = response.data;
                }).catch(console.error);
        },
        getItems(row, prop = null) {
            return axios.get('/api/price-product-report-items', {
                params: {
                    'data': row,
                    'prop' : prop,
                    'price' : this.reportPrice
                }
            })
            .then(response => {
                console.log(response);
                this.items = response.data;
                this.prop = prop;
            }).catch(console.error);
        },
        clearItemsReport() {
            this.items = [];
            this.prop = null;
        },
        sort(prop) {
            this.reverse = !this.reverse;

            if (prop === 'count') {
                if (this.reverse) {
                    this.results = this.results.sort((a, b) => a.count - b.count);
                    return
                }

                this.results = this.results.sort((a, b) => b.count - a.count);
                return;
            }

            this.results = this.results.sort(function(a, b) {
                var textA = a[prop] ? a[prop].toUpperCase() : null;
                var textB = b[prop] ? b[prop].toUpperCase() : null;

                if (textA == textB) {
                    return 0;
                } else if (textA == null) {
                    return 1;
                } else if (textB == null) {
                    return -1;
                }

                return (textA < textB) ? -1 : (textA > textB) ? 1 : 0;
            });

            if (this.reverse) {
                this.results.reverse();
            }
        }
    }
}
</script>
