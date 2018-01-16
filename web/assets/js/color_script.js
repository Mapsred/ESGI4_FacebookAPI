$(document).ready(function(){
    function removeClass(){
        $("body").removeClass("skin-blue skin-yellow skin-green skin-purple skin-red skin-black");
    }

    $('.btn-color').click(function () {
        var color = $(this).data("skin");
        if (null !== color) {
            removeClass();
            $("body").addClass(color);
        }
    });

    $(".btn-choice").click(function(){
        var color = $(this).data("skin");
        var sub_domain = $("body").data("sub_domain");
        var edit = Routing.generate('admin_colorEdit', { project_name: sub_domain});
        $.ajax({
            url: edit,
            type: "POST",
            data: {color: color},
            error: function () {
                alert('Problème dans la fonction de choix de couleur');
                console.log(color);
                console.log(sub_domain);
            },
            success: function (){
                window.location.href = Routing.generate('admin_index', { project_name: sub_domain});
            }
        });
    });

});


