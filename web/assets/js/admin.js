$(function() {
    var $keyValueContainer = {};
    $keyValueContainer.$template = $('#template-key-value-row');
    $keyValueContainer.$el = $('.container-key-value--init');

    $keyValueContainer.updateTextareaDataset = function($item) {
        var $data = $item.find('table tbody tr');

        var arrayOfData = [];
        $data.each(function() {
            var $row = $(this);

            arrayOfData.push({
                'key': $row.find('.key input').val(),
                'value': $row.find('.value input').val()
            });
        });

        $item.find('textarea').val(JSON.stringify(arrayOfData));
    };

    $keyValueContainer.init = function() {
        $keyValueContainer.$el.each(function() {
            var $item = $(this);
            var $textarea = $item.find('textarea');

            $textarea.hide();

            if ($textarea.val().trim().length > 0) {
                var data = JSON.parse($textarea.val());

                data.forEach(function(item) {
                    $item.find('table tbody').append($keyValueContainer.$template.html());
                    var $newEl = $item.find('table tbody tr:last-child');

                    $newEl.find('.key input').val(item.key);
                    $newEl.find('.value input').val(item.value);
                    $newEl.find('.btn-remove-item').on('click', function(e) {
                        $newEl.remove();
                        $keyValueContainer.updateTextareaDataset($item);

                        e.preventDefault();
                        e.stopPropagation();
                    });

                    $newEl.find('input').on('keyup', function() {
                        $keyValueContainer.updateTextareaDataset($item);
                    });
                });
            }

            $item.find('.btn-add-item').on('click', function(e) {
                $item.find('table tbody').append($keyValueContainer.$template.html());

                var $newEl = $item.find('table tbody tr:last-child');

                $newEl.find('.btn-remove-item').on('click', function(e) {
                    $newEl.remove();
                    $keyValueContainer.updateTextareaDataset($item);

                    e.preventDefault();
                    e.stopPropagation();
                });

                $newEl.find('input').on('keyup', function() {
                   $keyValueContainer.updateTextareaDataset($item);
                });

                e.preventDefault();
                e.stopPropagation();
            });
        });
    };

    if ($keyValueContainer.$el.length > 0) {
        $keyValueContainer.init();
    }

    $('.mobile-sidebar').on('click', function(e) {
        $('.sidebar, .content').toggleClass('mobile-sidebar-visible');
    });

    tinymce.init({
        selector: ".init-tinymce",
        relative_urls: false,
        remove_script_host: false,
        plugins: [
                "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "table contextmenu directionality emoticons template textcolor paste fullpage textcolor colorpicker textpattern"
        ],

        toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
        toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
        toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",

        menubar: false,
        toolbar_items_size: 'small',

        style_formats: [
                {title: 'Bold text', inline: 'b'},
                {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                {title: 'Example 1', inline: 'span', classes: 'example1'},
                {title: 'Example 2', inline: 'span', classes: 'example2'},
                {title: 'Table styles'},
                {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ],

        templates: [
                {title: 'Test template 1', content: 'Test 1'},
                {title: 'Test template 2', content: 'Test 2'}
        ]
    });

    var sidebarSet = function() {
        var $sidebar = $('.sidebar');
        var $content = $('.content');

        if ($content.outerHeight() > $sidebar.outerHeight()) {
            $sidebar.height($content.outerHeight());
        }

        if ($sidebar.innerHeight() < window.innerHeight) {
            $sidebar.innerHeight ( window.innerHeight );
        }
    };

    sidebarSet();
    $(window).on('scroll resize', function() {
        sidebarSet();
    });
});
