$(document).ready(function(){
    $(".alert").alert();
    $("[data-tooltip]").tooltip();

    $resizable = $(".resizable");
    $resizable.resizable({
        alsoResize: '.resizable .resizable-iframe',
    });

    $('#vote1').hover(function(){
        for(var j = 1; j <= 1; j++) {
            $('#vote' + j).removeClass('glyphicon-heart-empty').addClass('glyphicon-heart');
        }
    }, function(){
        for(var j = 1; j <= 1; j++) {
            $('#vote' + j).removeClass('glyphicon-heart').addClass('glyphicon-heart-empty');
        }
    });

    $('#vote2').hover(function(){
        for(var j = 1; j <= 2; j++) {
            $('#vote' + j).removeClass('glyphicon-heart-empty').addClass('glyphicon-heart');
        }
    }, function(){
        for(var j = 1; j <= 2; j++) {
            $('#vote' + j).removeClass('glyphicon-heart').addClass('glyphicon-heart-empty');
        }
    });

    $('#vote3').hover(function(){
        for(var j = 1; j <= 3; j++) {
            $('#vote' + j).removeClass('glyphicon-heart-empty').addClass('glyphicon-heart');
        }
    }, function(){
        for(var j = 1; j <= 3; j++) {
            $('#vote' + j).removeClass('glyphicon-heart').addClass('glyphicon-heart-empty');
        }
    });

    $('#vote4').hover(function(){
        for(var j = 1; j <= 4; j++) {
            $('#vote' + j).removeClass('glyphicon-heart-empty').addClass('glyphicon-heart');
        }
    }, function(){
        for(var j = 1; j <= 4; j++) {
            $('#vote' + j).removeClass('glyphicon-heart').addClass('glyphicon-heart-empty');
        }
    });

    $('#vote5').hover(function(){
        for(var j = 1; j <= 5; j++) {
            $('#vote' + j).removeClass('glyphicon-heart-empty').addClass('glyphicon-heart');
        }
    }, function(){
        for(var j = 1; j <= 5; j++) {
            $('#vote' + j).removeClass('glyphicon-heart').addClass('glyphicon-heart-empty');
        }
    });
});