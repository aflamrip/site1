(function($) {
    "use strict";
    // Language
    var __ = function(msgid) {
        return window.i18n[msgid] || msgid;
    };
    // Ajax Start Event
    $(document).ajaxStart(function() {
        $('body').addClass('loader-body');
    });
    // Ajax Complete Event
    $(document).ajaxComplete(function() {
        $('[data-tooltip="tooltip"]').tooltip({ trigger: "hover" });
        $('.lazy').lazy();
        $('body').removeClass('loader-body');
    });

    // Lazy Load
    if ($('.lazy').length > 0) {
        $('.lazy').lazy();
    }

    // Action Control
    $(".confirm").on("click", function(event) {
        if (confirm('Do you want to remove it ?')) {
            return true;
        } else {
            return false;
        }
    });
    // Cover Select
    $(".media-select .media-btn").on("click", function(event) {
        var id = $(this).attr('id');
        $('#file-' + id).click();
    });
    $(".media-input").on("change", function(event) {
        var id = $(this).attr('data-preview');
        $('.' + id).addClass('media-selected');
        if (this.files && this.files[0]) {

            var reader = new FileReader();
            reader.onload = function(e) {
                $('.' + id).css('background-image', 'url(' + e.target.result + ')');
                $('[name="image-url"]').val('');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
    // Aside Resize
    $(window).on('resize', function() {
        var $w = $(window).width(),
            $lg = 1200,
            $md = 991,
            $sm = 768;
        if ($w > $lg) {
            $('.aside-lg').modal('hide');
        }
        if ($w > $md) {
            $('#aside').modal('hide');
            $('.aside-md, .aside-sm').modal('hide');
        }
        if ($w > $sm) {
            $('.aside-sm').modal('hide');
        }
    });
    $('[data-tooltip="tooltip"]').tooltip({
        trigger: "hover",
        container: ".app-content"
    });

    // Modal
    $('body').on('click', '[data-toggle="modal"]', function() {
        $('.modal').modal('hide');
        $('.aside').modal('hide');
        $($(this).data("target") + ' .modal-dialog').load($(this).data("remote"), function() {});
    });
    $('.modal').on('hide.bs.modal', function(e) {
        $('.modal-dialog').html('');
    });
    // Themoviedb ajax
    $(".btn-insert").on("click", function(e) {
        var id = $(this).attr('data-ajax');
        var type = $(this).attr('data-type');
        if ($('input[name="actor"]').is(":checked")) {
            var actor = $('input[name="actor"]').val();
        }
        if ($('input[name="season"]').is(":checked")) {
            var season = $('input[name="season"]').val();
        }
        if ($('input[name="episode"]').is(":checked")) {
            var episode = $('input[name="episode"]').val();
        }
        if (id) {
            $.ajax({
                type: 'GET',
                url: URL + '/admin/tmdb?_ACTION=insert',
                dataType: 'json',
                data: 'id=' + id + '&type=' + type + '&actor=' + actor + '&season=' + season + '&episode=' + episode,
                success: function(resp) {
                    Snackbar.show({ text: resp.text, customClass: 'bg-' + resp.status });
                    if (resp.status == 'success') {
                        $('.card-item[data-id="' + id + '"]').remove();
                    }
                }
            });
        }
    });
    // Nav 
    $("[data-nav] a").on("click", function(e) {
        var $this = $(this),
            $active, $li, $li_li;

        $li = $this.parent();
        $li_li = $li.parents('li');

        $active = $li.closest("[data-nav]").find('.active');

        $li_li.addClass('active');
        ($this.next().is('ul') && $li.toggleClass('active')) || $li.addClass('active');

        $active.not($li_li).not($li).removeClass('active');

        if ($this.attr('href') && $this.attr('href') != '#') {
            $(document).trigger('Nav:changed');
        }
    });

    // Colorpicker
    $('.colorpicker').minicolors({
        control: $(this).attr('data-control') || 'hue',
        inline: $(this).attr('data-inline') === 'true',
        letterCase: 'lowercase',
        opacity: false,
        change: function(hex, opacity) {
            if (!hex) return;
            if (opacity) hex += ', ' + opacity;
            try {
                console.log(hex);
            } catch (e) {}
            $(this).select();
        },
        theme: 'bootstrap'
    });
    // Summernote
    $('.summernote').summernote({
        height: 240,
        defaultFontName: 'Inter',
        callbacks: {
            onPaste: function(e) {
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                e.preventDefault();
                document.execCommand('insertText', false, bufferText);
            },
            onImageUpload: function(note) {
                uploadImage(note[0]);
            }
        }
    });

    // Selectize
    $('.selectize-single').selectize();
    $('.selectize-ajax').selectize({
        valueField: 'id',
        labelField: 'name',
        searchField: 'name',
        options: [],
        maxItems: 1,
        load: function(query, callback) {
            if (!query.length) return callback();
            $.ajax({
                url: URL + '/ajax/' + this.settings.dataAjax + '?q=' + encodeURIComponent(query),
                type: 'GET',
                dataType: 'json',
                error: function() {
                    callback();
                },
                success: function(res) {
                    callback(res.data.slice(0, 10));
                }
            });
        },
        create: true,
        createFilter: function(input) {
            input = input.toLowerCase();
        }
    });
    // Slide Selectize Search
    $('.selectize-query').selectize({
        valueField: 'id',
        labelField: 'name',
        searchField: 'name',
        options: [],
        maxItems: 1,
        render: {
            option: function(item, escape) {
                return '<div class="d-flex align-items-center px-3 py-1">' +
                    '<div class="media media-cover-temp w-sm-thumb lazy" data-src="' + escape(item.image) + '"></div>' +
                    '<div class="ml-3">' +
                    (item.name ? '<div class="name">' + escape(item.name) + '</div>' : '') +
                    (item.type ? '<div class="text-muted text-12">' + escape(item.type) + '</div>' : '') +
                    '</div>' +
                    '</div>';
            }
        },
        load: function(query, callback) {
            if (!query.length) return callback();
            $.ajax({
                url: URL + '/admin/ajax/' + this.settings.dataAjax + '?q=' + encodeURIComponent(query),
                type: 'GET',
                dataType: 'json',
                error: function() {
                    callback();
                },
                success: function(res) {
                    callback(res.data.slice(0, 10));
                }
            });
        },
        create: false,
        onChange: function(value) {
            $.ajax({
                url: URL + '/admin/ajax/post?id=' + value,
                type: 'GET',
                dataType: 'json',
                success: function(resp) {
                    $('input[name="title"]').val(resp.data[0].name);
                }
            });
        }
    });
    // module block sortable
    $('.module-accordion').sortable({
        handle: '.sortable-move',
        forcePlaceholderSize: true,
        placeholder: 'sortable-placeholder',
        beforeStop: function(event, ui) {
            $(".module-accordion .card").each(function(i, el) {
                $(el).find('.sortable-input').val(i);
            });
        },
        create: function(event, ui) {
            $(".module-accordion .card").each(function(i, el) {
                $(el).find('.sortable-input').val(i);
            });
        }
    });
})(jQuery);