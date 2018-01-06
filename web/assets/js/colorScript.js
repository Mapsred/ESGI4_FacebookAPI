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
        $.ajax({
            url: 'admin_colorEdit',
            type: "POST",
            data: {
                Color: String("skin-blue")
            },
            error: function () {
                alert('Problème dans la fonction de choix de couleur');
            },
        }).done(function (){
            window.location.href = 'admin_colorChoice';
        });
    });

    $(".btn-yellow-choice").click(function(){
        $.ajax({
            url: 'admin_colorEdit',
            type: "POST",
            data: {
                Color: String("skin-yellow")
            },
            error: function () {
                alert('Problème dans la fonction de choix de couleur');
            },
        }).done(function (){
            window.location.href = 'admin_colorChoice';
        });
    });

    $(".btn-green-choice").click(function(){
        $.ajax({
            url: 'admin_colorEdit',
            type: "POST",
            data: {
                Color: String("skin-green")
            },
            error: function () {
                alert('Problème dans la fonction de choix de couleur');
            },
        }).done(function (){
            window.location.href = 'admin_colorChoice';
        });
    });

    $(".btn-purple-choice").click(function(){
        $.ajax({
            url: 'admin_colorEdit',
            type: "POST",
            data: {
                Color: String("skin-purple")
            },
            error: function () {
                alert('Problème dans la fonction de choix de couleur');
            },
        }).done(function (){
            window.location.href = 'admin_colorChoice';
        });
    });

    $(".btn-red-choice").click(function(){
        $.ajax({
            url: 'admin_colorEdit',
            type: "POST",
            data: {
                Color: String("skin-red")
            },
            error: function () {
                alert('Problème dans la fonction de choix de couleur');
            },
        }).done(function (){
            window.location.href = 'admin_colorChoice';
        });
    });

    $(".btn-black-choice").click(function(){
        $.ajax({
            url: 'admin_colorEdit',
            type: "POST",
            data: {
                Color: String("skin-black")
            },
            error: function () {
                alert('Problème dans la fonction de choix de couleur');
            },
        }).done(function (){
            window.location.href = 'admin_colorChoice';
        });
    });

});


