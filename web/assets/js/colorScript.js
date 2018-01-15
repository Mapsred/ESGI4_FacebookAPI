$(document).ready(function(){

    function removeClass(){
        $("body").removeClass("skin-blue");
        $("body").removeClass("skin-yellow");
        $("body").removeClass("skin-green");
        $("body").removeClass("skin-purple");
        $("body").removeClass("skin-red");
        $("body").removeClass("skin-black");
    }

    function addClassColor(color){
        color = $(color).data("skin");
        $("body").addClass(color);
    }

    $(".btn-blue").click(function(){
        removeClass();
        addClassColor(this);
    });

    $(".btn-yellow").click(function(){
        removeClass();
        addClassColor(this);
    });

    $(".btn-green").click(function(){
        removeClass();
        addClassColor(this);
    });

    $(".btn-purple").click(function(){
        removeClass();
        addClassColor(this);
    });

    $(".btn-red").click(function(){
        removeClass();
        addClassColor(this);
    });

    $(".btn-black").click(function(){
        removeClass();
        addClassColor(this);
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


