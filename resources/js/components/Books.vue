<template>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between pb-2 mb-2">
            <h5 class="card-title">All Books</h5>
            <div>
                <button class="btn btn-success" type="button" @click="this.$router.push({'name': 'addbook'})">New Book</button>
            </div>
        </div>

        <div v-if="strSuccess" class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>{{strSuccess}}</strong>
        </div>

        <div v-if="strError" class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>{{strError}}</strong>
        </div>
        
        <table class="table table-hover table-sm">
            <thead class="bg-dark text-light">
                <tr>
                    <th width="50" class="text-center">#</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>Description</th>
                    <th>ISBN</th>
                    <th>Published</th>
                    <th>Publisher</th>
                    <th class="text-center" width="120">Image</th>
                    <th class="text-center" width="200">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(book, index) in books.data" :key="book.id">
                    <td class="text-center">{{index+1}}.</td>
                    <td>{{book.title}}</td>
                    <td>{{book.author}}</td>
                    <td>{{book.genre}}</td>
                    <td>{{book.description}}</td>
                    <td>{{book.isbn}}</td>
                    <td>{{book.published}}</td>
                    <td>{{book.publisher}}</td>
                    <td class="text-center">
                        <div v-if="book.image">
                            <img alt="book-img" width="100" v-bind:src="'/image/' +book.image">
                        </div>
                    </td>
                    <td class="text-center">
                        <router-link :to="{name:'editbook', params: {id:book.id}}" class="btn btn-warning">Edit</router-link>&nbsp;
                        <button class="btn btn-danger" @click="deletebook(book.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <Bootstrap5Pagination
        :data="books"
        @pagination-change-page="getResults"
    />

    </div>
</div>


</template>

<script>
    import { Bootstrap5Pagination } from 'laravel-vue-pagination';

    export default {
        components: {
            Bootstrap5Pagination
        },
        data() {
            return {
                books: [],
                strSuccess: '',
                strError: '',
            }
        },
        created() {
           this.getResults();
        },
        methods: {
            getResults(page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }
                this.$axios.get('/api/books?page=' + page)
                .then(response => {
                    console.log("response",response);
                    this.books = response.data.data;
                    
                })
                .catch(function(error) {
                    console.log(error);
                });
            },
            deletebook(id) {
                if(confirm("Do you really want to delete this data?")) {
                    this.$axios.delete(`/api/books/delete/${id}`)
                    .then(response => {
                        console.log("response",response);
                        this.getResults();
                        this.strError = "";
                        this.strSuccess = response.data.message;

                    })
                    .catch(function(error) {
                        this.strError = error.response.data.message;
                        this.strSuccess = "";
                    });
                }
            }
        },
        
    }

</script>