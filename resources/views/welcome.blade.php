<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


    <script src="https://cdn.jsdelivr.net/npm/vue@2.7.14/dist/vue.js"></script>
</head>

<body class="antialiased">
    <div id="app">

        <div class="row">
            <form class="form-inline">
                <div class="form-group">
                    <label for="search">Search</label>
                    <input id="search" class="form-control mb-2 mr-sm-2" type="text" v-model="search">
                </div>
                <div class="form-group">
                    <select class="form-control mb-2 mr-sm-2" id="sort" v-model="sort">
                        <option value="id">ID</option>
                        <option value="title">Title</option>
                        <option value="text">Text</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-12 col-md-4" v-for="article in articles.data">
                <div class="card">
                    <div class="card-header">
                        <h1>@{{ article.title }}</h1>
                    </div>
                    <div class="card-body" v-html="article.text"></div>
                </div>
            </div>
        </div>


    </div>
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/vue@2.7.14/dist/vue.js"></script>

    <script>
        new Vue({
            el: '#app',
            data: {
                articles: '',
                search: '',
                sort: '', 
                usr: '', 
            },
            watch: {
                search: function(newVal, oldVal) {
                    this.articles = axios
                        .post(this.url + 'api/articles/search', {
                            params: {
                                search: newVal,
                                sort: this.sort
                            }
                        })
                        .then(response => (this.articles = response));
                },
                sort: function(newVal, oldVal) {
                    this.articles = axios
                        .post( this.url + 'api/articles/search', {
                            params: {
                                search: this.search,
                                sort: newVal
                            }
                        })
                        .then(response => (this.articles = response));
                }
            },
            mounted() {
                this.url = window.location.href;
                this.articles = axios
                    .post(this.url + 'api/articles',)
                    .then(response => (this.articles = response));
            }
        })
    </script>
</body>

</html>