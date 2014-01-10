function refreshContent($textEditor) {
    $textEditor.parent().find('.text-editor-hidden').attr('value', $textEditor.html());
}

$(document).on('ready', function(){

    $('form .text-editor').on('keyup', function(){
        refreshContent($(this));
    });

    $('form').on('submit', function(){
        refreshContent($(this).find('.text-editor'));
    });

    $('.text-editor-controls').on('mousedown', '.btn', function(){
        var action = $(this).data('action');
        var $textEditor = $(this).parent().parent().parent().find('.text-editor');

        if(action == 'bold') {
            prefixSuffix('**', $textEditor);
        } else if(action == 'italic') {
            prefixSuffix('*', $textEditor);
        } else if(action == 'underline') {
            prefixSuffix('IDK WHAT TO DO', $textEditor);
        } else if(action == 'strikethrough') {
            prefixSuffix('--', $textEditor);
        }

        //alert($(this).data('action') + ' ' + $textEditor.html());
    });
});

function prefixSuffix(prefix, $textEditor, suffix) {
    if(typeof suffix == 'undefined') {
        suffix = '';
    }
    if(window.getSelection) {
        var sel = window.getSelection();
        if(sel.rangeCount) {
            var range = sel.getRangeAt(0);
            var text = range.toString();
            range.deleteContents();
            range.insertNode(document.createTextNode(prefix + text + prefix + suffix));
        }
    } else if(document.selection && document.selection.createRange) {
        var range = document.selection.createRange();
        var text = range.toString();
        range.text = prefix + text + prefix;
    } else {
        $textEditor.innerHtml = $textEditor.innerHtml + prefix + prefix + suffix;
    }
}