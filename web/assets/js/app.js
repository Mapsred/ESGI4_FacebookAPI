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

var Copy = {
    init: function () {
        $('.clipboard-copy').tooltip({
            trigger: 'click',
            placement: 'bottom'
        });

        var clipboard = new Clipboard('.clipboard-copy');

        clipboard.on('success', function (e) {
            Copy.toggle('Copié!', e.trigger);
        });

        clipboard.on('error', function (e) {
            Copy.toggle('Echec!', e.trigger);
        });

    },

    toggle: function (message, trigger) {
        Copy.setTooltip(message, trigger);
        Copy.hideTooltip(trigger);
    },

    setTooltip: function (message, trigger) {
        $(trigger).tooltip('hide').attr('data-original-title', message).tooltip('show');
    },

    hideTooltip: function (trigger) {
        setTimeout(function () {
            $(trigger).tooltip('hide');
        }, 3000);
    }
};

$(document).ready(function () {
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    if (typeof lightbox !== 'undefined') {
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true
        });
    }

    if (document.getElementById('largeForm')) {
        Album.init();
    }

    Copy.init();

});