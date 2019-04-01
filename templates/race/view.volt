{% extends 'layouts/app.volt' %}

{% block content %}
    <div class="row">
        <div class="col-12">
            <h2>Race Results</h2>
            {{ print_race(race) }}
        </div>
    </div>
{% endblock %}