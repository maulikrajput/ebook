<template>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between pb-2 mb-2">
                <h5 class="card-title">Update Book Data</h5>
                <div>
                    <router-link :to="{name: 'books'}" class="btn btn-success">Go Back</router-link>
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


            <form @submit.prevent="updatePost" enctype="multipart/form-data">
                <div class="form-group mb-2">
                    <label>Title</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" v-model="title" placeholder="Enter book title">
                </div>

                <div class="form-group mb-2">
                    <label>Author</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" v-model="author" placeholder="Enter Book Author">
                </div>
                
                
                <div class="form-group mb-2">
                    <label>Genre</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" v-model="genre" placeholder="Enter Book Genre">
                </div>

                <div class="form-group mb-2">
                    <label>Description</label><span class="text-danger"> *</span>
                   <textarea class="form-control" rows="3" v-model="description" placeholder="Enter Book Description"></textarea>
                </div>

                <div class="form-group mb-2">
                    <label>ISBN</label><span class="text-danger"> *</span>
                    <input type="number" class="form-control" v-model="isbn" placeholder="Enter Book ISBN">
                </div>

                <div class="form-gorup mb-2">
                    <label>Image</label><span class="text-danger"> *</span>
                    <input type="file" class="form-control mb-2" v-on:change="onChange">

                    <div v-if="imgPreview">
                        <img v-bind:src="imgPreview" width="100" height="100"/>
                    </div>
                </div>

                <div class="form-group mb-2">
                    <label>Published Date</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control" v-model="published" placeholder="Enter Book Published Date">
                </div>
                
                <div class="form-group mb-2">
                    <label>Publisher</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" v-model="publisher" placeholder="Enter Book Publisher">
                </div>

                <button type="submit" class="btn btn-primary mt-4 mb-4"> Update Book</button>

            </form>
            
        </div>
    </div>
</template>

<script>
export default{
    data() {
        return {
            id:'',
            title: '',
            author: '',
            genre: '',
            description: '',
            isbn: '',
            image: '',
            published: '',
            publisher: '',
            img: '',
            strSuccess: '',
            strError: '',
            imgPreview: null
        }
    },

    created() {
        this.$axios.get(`/api/books/edit/${this.$route.params.id}`)
        .then(response => {
            this.name = response.data.data['name'];
            this.title = response.data.data['title'];
            this.author = response.data.data['author'];
            this.genre = response.data.data['genre'];
            this.description = response.data.data['description'];
            this.isbn = response.data.data['isbn'];
            this.published = response.data.data['published'];
            this.publisher = response.data.data['publisher'];
            this.imgPreview = "/image/"+response.data.data['image'];
        })
        .catch(function(error) {
            console.log(error);
        });
    },
    methods: {
        onChange(e) {
            this.image = e.target.files[0];
            let reader = new FileReader();
            reader.addEventListener("load", function () {
                this.imgPreview = reader.result;
            }.bind(this), false);

            if (this.image) {
                if ( /\.(jpe?g|png|gif)$/i.test( this.image.name ) ) {
                    reader.readAsDataURL( this.image );
                }
            }
        },
        updatePost(e) {
            let existingObj = this;
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                const formData = new FormData();
                formData.append('title', this.title);
                formData.append('author', this.author);
                formData.append('genre', this.genre);
                formData.append('description', this.description);
                formData.append('isbn', this.isbn);
                if (this.image) {
                    formData.append('file', this.image);
                }
                formData.append('published', this.published);
                formData.append('publisher', this.publisher);

                this.$axios.post(`/api/books/update/${this.$route.params.id}`, formData, config)
                .then(response => {
                    this.strError = "";
                    this.strSuccess = response.data.message;
                    setTimeout(() => {
                        this.$router.push({'name': 'books'});
                    },800);
                })
                .catch(function(error) {
                    existingObj.strSuccess ="";
                    existingObj.strError = error.response.data.message;
                });
        }

    },
}

</script>