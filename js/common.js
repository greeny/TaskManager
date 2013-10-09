function fillValues(id, values) {
    var $form = $(id);
    for(var key in values) {
        if(key == 'submit') {
            $form.find("[name='_" + key + "']").val(values[key]);
        } else if(key == 'header') {
            $form.parent().find('.modal-title').text(values[key]);
        } else {
            $form.find("[name='" + key + "']").val(values[key]);
        }
    }
}

$(document).ready(function(){
    $(".alert").alert();
    $("[data-tooltip]").tooltip();

    $.datepicker.setDefaults($.datepicker.regional['cs']);
    $('.input-datepicker').datepicker();

    $resizable = $(".resizable");
    $resizable.resizable({
        alsoResize: '.resizable .resizable-iframe'
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