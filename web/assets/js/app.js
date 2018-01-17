var Album = {
    init: function () {
        $(".select_all").click(function () {
            var parent = $(this).closest('table');
            parent.find("td.input input").each(function () {
                $(this).prop("checked", !$(this).prop("checked"));
            });

            Album.buttonToggle();
        });

        $("td.input input").click(function () {
            Album.buttonToggle();
        })

    },

    buttonToggle: function () {
        $("#btn").prop("disabled", true);
        $("td.input").each(function () {
            var checkbox = $(this).find("input");
            if ($(checkbox).prop("checked") === true) {
                $("#btn").prop("disabled", false);
            }
        });

    }
};

$(document).ready(function () {

    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true
    });

    if (document.getElementById('largeForm')) {
        Album.init();
    }


});