{% include 'helpers/macros.volt' %}
<html lang="en">
    <head>
        <title>{{ title }}</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <style>
            progress.bar {
                vertical-align: middle;
                height: 4px;
                width: 50px;
            }

            .horse-item {
                border-bottom: 1px dashed #bbb;
            }
        </style>
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container">
                    <a class="navbar-brand" href="/">Horse Race</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/latest">Latest</a>
                            </li>
                        </ul>
                        <form action="/race/progress" method="post" class="form-inline my-2 my-lg-0 mr-2">
                            <button type="submit" class="btn btn-block btn-sm btn-success">Progress</button>
                        </form>
                        <form action="/race" method="post" class="form-inline my-2 my-lg-0">
                            <button type="submit" class="btn btn-block btn-sm btn-dark">Create Race</button>
                        </form>
                    </div>
                </div>
            </nav>
        </header>
        <div class="container pt-2">
            {% block content %}{% endblock %}
        </div>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
        <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script src="/js/jquery-3.3.1.min.js"></script>
        <script src="/js/app.js"></script>
        <script type="text/javascript">
            var times = document.getElementsByClassName('time-moment');

            for (var i = 0; i < times.length; i++) {
                times[i].textContent = moment(times[i].textContent).fromNow();
            }
        </script>
    </body>
</html>
