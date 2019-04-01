{%- macro print_race(race) %}
<div class="list-group-item " data-id="{{ race.id }}">
    <div class="d-flex w-100 justify-content-between">
        <h5 class="mb-1">Race <a href="/race/{{ race.id }}">#{{ race.hash }}</a></h5>
        {% if !race.finished() %}
        <small><a href="/race/{{ race.id }}/tick">Advance Race</a></small>
        {% endif %}
    </div>
    <div class="mb-1">
        <ol class="text-monospace">
            {% for ind, horse in race.getHorses() %}
            <li class="py-1 horse-item{{ horse.race_finished?' text-success':''}}">
                <strong>Horse: {{ horse.hash }} | Stats: </strong>[
                Speed: {{ float(horse.horse.speed, 5) }} m/s Str: {{ float(horse.horse.strength, 5) }} End: {{ float(horse.horse.endurance, 5) }} ]<br>
                <span>End: <progress class="bar" max="100" value="{{ (horse.endurance / horse.horse.endurance) * 100 }}"></progress></span>
                <span>Pos: <progress class="bar" max="100" value="{{ (horse.position / race.length) * 100 }}"></progress></span>
                {{ replace(' ', '&nbsp;', pad(number_format(horse.position, 2), 8, ' ')) }} m
                Time: {{ number_format(horse.timer, 2) }}
            </li>
            {% endfor %}
        </ol>
    </div>

    <small class="time-moment">{{ race.created_at }}</small>
</div>
{%- endmacro %}