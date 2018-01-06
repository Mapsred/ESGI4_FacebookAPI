$(document).ready(function(){

    function removeClass(){
        $("body").removeClass("skin-blue");
        $("body").removeClass("skin-yellow");
        $("body").removeClass("skin-green");
        $("body").removeClass("skin-purple");
        $("body").removeClass("skin-red");
        $("body").removeClass("skin-black");
    }

    function addClassBlue(){
        $("body").addClass("skin-blue");
    }

    function addClassYellow(){
        $("body").addClass("skin-yellow");
    }

    function addClassGreen(){
        $("body").addClass("skin-green");
    }

    function addClassPurple(){
        $("body").addClass("skin-purple");
    }

    function addClassRed(){
        $("body").addClass("skin-red");
    }

    function addClassBlack(){
        $("body").addClass("skin-black");
    }

    $(".btn-blue").click(function(){
        removeClass();
        addClassBlue();
    });

    $(".btn-yellow").click(function(){
        removeClass();
        addClassYellow();
    });

    $(".btn-green").click(function(){
        removeClass();
        addClassGreen();
    });

    $(".btn-purple").click(function(){
        removeClass();
        addClassPurple();
    });

    $(".btn-red").click(function(){
        removeClass();
        addClassRed();
    });

    $(".btn-black").click(function(){
        removeClass();
        addClassBlack();
    });

    $(".btn-blue-choice").click(function(){
        var edit = Routing.generate('admin_colorEdit', { color: "skin-blue" });
        $.ajax({
            url: edit,
            type: "POST",
            error: function () {
                alert('Problème dans la fonction de choix de couleur');
            },
        }).done(function (){
            window.location.href = 'admin_index';
        });
    });

    $(".btn-yellow-choice").click(function(){
        var edit = Routing.generate('admin_colorEdit', { color: "skin-yellow" });
        $.ajax({
            url: edit,
            type: "POST",
            error: function () {
                alert('Problème dans la fonction de choix de couleur');
            },
        }).done(function (){
            window.location.href = 'admin_index';
        });
    });

    $(".btn-green-choice").click(function(){
        var edit = Routing.generate('admin_colorEdit', { color: "skin-green" });
        $.ajax({
            url: edit,
            type: "POST",
            error: function () {
                alert('Problème dans la fonction de choix de couleur');
            },
        }).done(function (){
            window.location.href = 'admin_index';
        });
    });

    $(".btn-purple-choice").click(function(){
        var edit = Routing.generate('admin_colorEdit', { color: "skin-purple" });
        $.ajax({
            url: edit,
            type: "POST",
            error: function () {
                alert('Problème dans la fonction de choix de couleur');
            },
        }).done(function (){
            window.location.href = 'admin_index';
        });
    });

    $(".btn-red-choice").click(function(){
        var edit = Routing.generate('admin_colorEdit', { color: "skin-red" });
        $.ajax({
            url: edit,
            type: "POST",
            error: function () {
                alert('Problème dans la fonction de choix de couleur');
            },
        }).done(function (){
            window.location.href = 'admin_index';
        });
    });

    $(".btn-black-choice").click(function(){
        var edit = Routing.generate('admin_colorEdit', { color: "skin-black" });
        $.ajax({
            url: edit,
            type: "POST",
            error: function () {
                alert('Problème dans la fonction de choix de couleur');
            },
        }).done(function (){
            window.location.href = 'admin_index';
        });
    });

});


