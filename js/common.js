function dump(v) {
    console.log(v);
    return v;
}

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

function toggleFolder(id, modify, animate) {
    if(typeof(Storage) !== 'undefined') {
        if(typeof(modify) === 'undefined') {
            modify = true;
        }
        if(modify != false) {
            var did = false;
            for(var key in JSON.parse(localStorage.closedFolders)) {
                if(key == id) {
                    var arr = JSON.parse(localStorage.closedFolders);
                    delete arr[key];
                    localStorage.closedFolders = JSON.stringify(arr);
                    did = true;
                }
            }
            if(did == false) {
                var arr = JSON.parse(localStorage.closedFolders);
                arr[id] = 'closed';
                localStorage.closedFolders = JSON.stringify(arr);
            }
        }
    }

    animate = typeof(animate) === 'undefined' ? 150 : animate;

    $item = $('#folder-'+id);

    $item.parent().children('ul').animate({
        height: 'toggle'
    }, animate);
    $item.toggleClass('icon-folder-open');
    $item.toggleClass('icon-folder-close');
}

function togglePanel(animate) {
    if(typeof(animate) === 'undefined') {
        animate = 350;
    } else {
        animate = 0;
    }
    var $icon = $('#left-panel-toggle-icon');
    var $panel = $('#left-panel');
    var $tabs = $('#left-tabs');
    var $content = $('#content');

    if($icon.hasClass('icon-chevron-left')) {
        $panel.animate({
            left: '-25%'
        }, animate, 'swing', function(){
            var $icon = $('#left-panel-toggle-icon');
            $icon
                .removeClass('icon-chevron-left')
                .addClass('icon-chevron-right');
        });

        $tabs.animate({
            left: -30
        }, animate);

        $content.animate({
            left: -30
        }, animate);

        if(typeof(Storage) !== 'undefined') {
            localStorage.panel = 'collapsed';
        }
    } else {
        $panel.animate({
            left: 0
        }, animate, 'swing', function(){
            var $icon = $('#left-panel-toggle-icon');
            $icon
                .removeClass('icon-chevron-right')
                .addClass('icon-chevron-left');
        });

        $tabs.animate({
            left: '20%'
        }, animate);

        $content.animate({
            left: '20%'
        }, animate);

        if(typeof(Storage) !== 'undefined') {
            localStorage.panel = 'expanded';
        }
    }
}

$(document).on('ready', function(){
    var $toggle = $('#left-panel-toggle');
    var $tabs = $('#left-tabs');

    if(typeof(Storage) !== 'undefined') {
        if(typeof(localStorage.panel) === 'undefined') {
            localStorage.panel = 'expanded';
        }

        if(typeof(localStorage.closedFolders) === 'undefined') {
            localStorage.closedFolders = JSON.stringify({});
        }

        if(localStorage.panel === 'collapsed') {
            togglePanel(false);
        }

        for(var id in JSON.parse(localStorage.closedFolders)) {
            toggleFolder(id, false, 0);
        }
    }

    $toggle.on('click', function(){
        togglePanel();
        return false;
    });

    $('li.auto-expand').on('click', 'a', function(){
        if(typeof(Storage) !== 'undefined') {
            if(localStorage.panel === 'collapsed') {
                localStorage.panel = 'expanded';
            }
        }
    });

    $('a[data-confirm]').on('click', function(){
        return confirm($(this).data('confirm'));
    });

    $('[data-folder]').on('click', function(){
        toggleFolder($(this).data('folder'), true, 150);
    });
});