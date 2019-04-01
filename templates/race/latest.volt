{% extends 'layouts/app.volt' %}
{% block content %}
    {{ flash.output() }}
    <div class="row">
        <div class="col-12">
            <h2>Last Races {{ races|length }}</h2>
            <div class="list-group">
            {% for race in races %}
                {{ print_race(race) }}
            {% else %}
                <div class="alert alert-dark text-center">No races. Create one <a href="/race">here</a>.</div>
            {% endfor %}
            </div>
        </div>
    </div>
{% endblock %}