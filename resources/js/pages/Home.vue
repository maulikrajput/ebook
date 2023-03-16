<template>
  <div class="container">
    <div class="row">
      <!-- BEGIN SEARCH RESULT -->
      <div class="col-md-12">
        <div class="grid search">
          <div class="grid-body">
            <div class="row">
              <!-- BEGIN FILTERS -->
              <div class="col-md-3">
                <h2 class="grid-title"><i class="fa fa-filter"></i> Filters</h2>
                <hr>

                <!-- BEGIN FILTER BY genre -->
                <h4>By genre:</h4>
                <p>
                  <button class="btn btn-default" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    show/hide genre
                  </button>
                </p>
                <div class="collapse" id="collapseExample">
                  <div v-for="genre in genres">
                    <div class="checkbox">
                      <label> <input type="checkbox" class="icheck" :value="genre.genre" :id="genre.genre"
                          v-model="selectedGenres" @click="check($event)"> {{ genre.genre }}</label>
                    </div>
                  </div>
                </div>


                <!-- END FILTER BY genre -->

                <div class="padding"></div>

                <!-- BEGIN FILTER BY DATE -->
                <h4>By Published Date:</h4>
                From
                <div class="input-group date form_date" data-date-format="dd-mm-yyyy" data-link-field="dtp_input1">
                  <input type="date" class="form-control" v-model="publishedFrom" @change="check($event)">
                </div>
                To
                <div class="input-group date form_date" data-date-format="dd-mm-yyyy" data-link-field="dtp_input2">
                  <input type="date" class="form-control" v-model="publishedTo" @change="check($event)">
                </div>
                <!-- END FILTER BY DATE -->

                <div class="padding"></div>

              </div>
              <!-- END FILTERS -->
              <!-- BEGIN RESULT -->
              <div class="col-md-9">
                <h2><i class="fa fa-file-o"></i> Result</h2>
                <hr>
                <!-- BEGIN SEARCH INPUT -->
                <div class="input-group">
                  <input type="text" placeholder="Search by title, author or ISBN" v-model="query" class="form-control">
                  <span class="input-group-btn">
                    <button class="btn btn-primary" v-on:click="searchResult" type="button"><i
                        class="fa fa-search"></i></button>
                  </span>
                </div>
                <!-- END SEARCH INPUT -->
                <p v-if="showWhatSearched">Showing all results matching "{{query}}"</p>

                <div class="padding"></div>

                <div class="row">
                  <!-- BEGIN ORDER RESULT -->


                  <div class="col-sm-6">
                    <div class="dropdown">
                      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton1"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Order by ({{ this.orderBy == 'recently_published' ? 'Recently Published' : 'Title' }}) <span
                          class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" @click="setORderBy('title')" href="#">Title</a></li>
                        <li><a class="dropdown-item" @click="setORderBy('recently_published')" href="#">Recently
                            Published</a></li>
                      </ul>
                    </div>
                  </div>
                  <!-- END ORDER RESULT -->

                </div>

                <!-- BEGIN TABLE RESULT -->
                <div class="table-responsive">
                  <table class="table table-hover">
                    <tbody>
                      <tr v-for="result in results.data" :key="result.id" v-on:click="this.$router.push({ name: 'product', params: { 'id': result.id } })">
                        <td class="image"><img v-bind:src="'image/' + result.image" width="50" height="50" /></td>
                        <td class="product"><strong>{{ result.title }}</strong><br>{{ result.description.substring(0, 150)
                          + '...' }}</td>
                        <td class="product">
                          <strong>Genre</strong><br>{{ result.genre }}
                        </td>
                        <td class="product">
                          <strong>ISBN</strong><br>{{ result.isbn }}
                          <strong>Published At </strong><br>{{ result.published }}
                        </td>
                      </tr>
                    </tbody>
                  </table>

                </div>
                <!-- END TABLE RESULT -->
                <!-- END PAGINATION -->
                <Bootstrap5Pagination :data="results" @pagination-change-page="searchResult" />
              </div>
              <!-- END RESULT -->
            </div>
          </div>
        </div>
      </div>
      <!-- END SEARCH RESULT -->
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
      query: '',
      orderBy: 'recently_published',
      publishedFrom: '',
      publishedTo: '',
      results: [],
      genres: [],
      selectedGenres: [],
      showWhatSearched: false

    }
  },
  created() {
    this.searchResult();
    this.getGenre();
  },
  methods: {
    check: function (e) {
      if (e.target.checked) {
        console.log(e.target.value)
      }
      setTimeout(() => {
        this.searchResult();
      }, 500)
    },
    searchResult(page) {
      this.showWhatSearched = false;
      if (typeof page === 'undefined') {
        page = 1;
      }
      this.results = [];
      this.$axios.post('/api/search?page=' + page, {
        query: this.query,
        orderBy: this.orderBy,
        selectedGenres: this.selectedGenres,
        publishedFrom: this.publishedFrom,
        publishedTo: this.publishedTo
      }).then(response => {
        this.results = response.data.data;
        if (this.query) {
          this.showWhatSearched = true;
        }
      });
    },
    getGenre() {
      this.$axios.get('/api/genres').then(response => {
        this.genres = response.data.data;
      });
    },
    setORderBy(order) {
      this.orderBy = order;
      this.searchResult();
    }
  }
}
</script>
<style>
body {
  margin-top: 20px;
  background: #eee;
}

.btn {
  margin-bottom: 5px;
}

.grid {
  position: relative;
  width: 100%;
  background: #fff;
  color: #666666;
  border-radius: 2px;
  margin-bottom: 25px;
  box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.1);
}

.grid .grid-body {
  padding: 15px 20px 15px 20px;
  font-size: 0.9em;
  line-height: 1.9em;
}

.search table tr td.rate {
  color: #f39c12;
  line-height: 50px;
}

.search table tr:hover {
  cursor: pointer;
}

.search table tr td.image {
  width: 50px;
}

.search table tr td img {
  width: 50px;
  height: 50px;
}

.search table tr td.rate {
  color: #f39c12;
  line-height: 50px;
}

.search table tr td.price {
  font-size: 1.5em;
  line-height: 50px;
}

.search #price1,
.search #price2 {
  display: inline;
  font-weight: 600;
}</style>