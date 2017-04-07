<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-contents">
                <span class="card-title">
                    Import Event Decklists
                </span>
                <div id="eventDetails">
                    <!-- todo: check the name and date, if there is an exact match, don't create a new tournament -->
                    <div class="row">
                        <div class="input-field col s10">
                            <!-- todo: query for event names -->
                            <input placeholder="Event Name" id="event_name" type="text">
                            <label for="event_name">Event Name</label>
                        </div>
                        <div class="input-field col s2">
                            <input type="date" class="datepicker">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="decks_holder"><!-- When you add a deck, it will create a new row and card here with all the fields --></div>
<div class="row">
    <div class="col s12 m8 l4 offset-m2 offset-l4 center">
        <a class="btn-large">Add Deck</a>
    </div>
</div>
<div class="row">
    <div class="col s12 m8 l4">
        <div class="card-panel">

        </div>
    </div>
</div>
