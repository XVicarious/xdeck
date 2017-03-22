require(["jquery", "moment"], function($, moment) {
    $.post("php/list_decks.php", function(data) {
        data = JSON.parse(data);
        var $recentDecks = $("#recentDecks");
        for(var i = 0; i < data.length; i++) {
            var item = "<a class=\"collection-item\"><div>";
            item += data[i]["dck_formats_name"] + " " + data[i]["dck_archetypes_name"];
            item += "<span class=\"secondary-content\">" + moment(data[i]["dck_decks_date"]).format("MMMM DDDo YYYY") + "</span>";
            item += "</div></a>";
            $recentDecks.append(item);
        }
    });
});
