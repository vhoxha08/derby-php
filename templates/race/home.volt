{% extends 'layouts/app.volt' %}
{% block content %}
    {{ flash.output() }}
    <div class="row">
        <div class="col-12">
            <h2>Best Time</h2>
            {% if !best %}
                <div class="alert alert-dark text-center">No best time is set yet.</div>
            {% else %}
            <div class="list-group-item " data-id="{{ best.race.id }}">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">Race <a href="/race/{{ best.race.id }}">#{{ best.race.hash }}</a></h5>
                </div>
                <div class="mb-1">
                    <ol class="text-monospace">
                        <li class="py-1 horse-item{{ best.race_finished?' text-warning':''}}">
                            <strong>Horse: {{ best.hash }} | Stats: </strong>[
                            Speed: {{ float(best.horse.speed, 5) }} m/s Str: {{ float(best.horse.strength, 5) }} End: {{ float(best.horse.endurance, 5) }} ]<br>
                            <span>End: <progress class="bar" max="100" value="{{ (best.endurance / best.horse.endurance) * 100 }}"></progress></span>
                            <span>Pos: <progress class="bar" max="100" value="{{ (best.position / best.race.length) * 100 }}"></progress></span>
                            {{ replace(' ', '&nbsp;', pad(number_format(best.position, 2), 8, ' ')) }} m
                            Time: {{ number_format(best.timer, 2) }}
                        </li>
                    </ol>
                </div>

                <small class="time-moment">{{ best.race.created_at }}</small>
            </div>
            {% endif %}<hr>
            <h2>Unfinished races {{ races|length }}</h2>
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