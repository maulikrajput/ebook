<template>
<div class="container mt-5 mb-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="row">
                    <div class="col-md-6">
                        <div class="images p-3">
                            <div class="text-center p-4"> <img id="main-image" v-bind:src="'/image/'+result.image" width="350" /> </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="product p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center"> <i class="fa fa-long-arrow-left"></i>
                                    <router-link class="text-decoration-none" :to="{name:'home'}" >
                                        <span class="ml-1">Back</span>    
                                    </router-link>
                                     </div> <i class="fa fa-shopping-cart text-muted"></i>
                            </div>
                            <div class="mt-4 mb-3"> <span class="text-uppercase text-muted brand">{{ result.genre }}</span>
                                <h5 class="text-uppercase">{{ result.title }}</h5>
                            </div>
                            <p class="about">{{ result.description }}</p>
                            <div class="sizes mt-5">
                                <h6 class="text-uppercase">ISBN</h6> <span>{{ result.isbn }}</span>
                            </div>
                            <div class="sizes mt-5">
                                <h6 class="text-uppercase">Published</h6> <span>{{ result.published }}</span>
                            </div>
                            <div class="sizes mt-5">
                                <h6 class="text-uppercase">Publisher</h6> <span>{{ result.publisher }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            query: '',
            result: {}
        }
    },
    created() {
        this.$axios.get(`/api/product/${this.$route.params.id}`)
        .then(response => {
            this.result = response.data.data;
        })
        .catch(function(error) {
            console.log(error);
        });
    }
}
</script>