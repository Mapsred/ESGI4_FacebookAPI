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

});


