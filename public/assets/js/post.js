(function($) {
    "use strict";
    // Language
    var __ = function(msgid) {
        return window.i18n[msgid] || msgid;
    };
    // remove video
    $('body').on('click', '.btn-video', function() {
        var id = $(".videos-accordion .card").last().data('id') + 1;
        if (id) {
            var id = id;
        } else {
            var id = 1;
        }
        var videoDetail = [
            { id: id, player: null }
        ];
        $('#card-video').tmpl(videoDetail).appendTo('.videos-accordion');

        // get services
        $('.load-option').each(function() {
            var type = $(this).attr('data-type');
            var selected = $(this).attr('data-id');
            var input = $(this);
            $.ajax({
                type: 'POST',
                url: URL + '/admin/ajax/' + type,
                dataType: 'json',
                success: function(resp) {
                    var Options = '';
                    $.each(resp.data, function(i, val) {
                        if (selected == resp.data[i].id) {
                            Options += '<option value="' + resp.data[i].id + '" selected="true">' + resp.data[i].name + '</option>';
                        } else {
                            Options += '<option value="' + resp.data[i].id + '">' + resp.data[i].name + '</option>';
                        }
                    });
                    input.html(Options);
                }
            });
        });
        $('#c-' + id).collapse({
            toggle: true
        });
        $('html, body').animate({
            scrollTop: $('#c-' + id).offset().top
        }, 'slow');
        $(".videos-accordion .card").each(function(i, el) {
            $(el).find('.sortable-input').val(i);
        });
    });

    // get services
    $('.load-option').each(function() {
        var type = $(this).attr('data-type');
        var selected = $(this).attr('data-id');
        var input = $(this);
        $.ajax({
            type: 'POST',
            url: URL + '/admin/ajax/' + type,
            dataType: 'json',
            success: function(resp) {
                var Options = '';
                $.each(resp.data, function(i, val) {
                    if (selected == resp.data[i].id) {
                        Options += '<option value="' + resp.data[i].id + '" selected="true">' + resp.data[i].name + '</option>';
                    } else {
                        Options += '<option value="' + resp.data[i].id + '">' + resp.data[i].name + '</option>';
                    }
                });
                input.html(Options);
            }
        });
    });
    $(document).on('keyup', '[name="name[]"]', function() {
        var val = $(this).val();
        var id = $(this).data('card');
        $('[data-id="' + id + '"').find('.name span').text(val);
    });
    $(document).on('change', '[data-type="language"]', function() {
        var val = $(this).find('option:selected').val();
        var text = $(this).find('option:selected').text();
        var id = $(this).data('card');
        if (val) {
            $('[data-id="' + id + '"').find('.name div').text(text);
            $(this).attr('data-id', val);
        }
    });
    $(document).on('change', '[data-type="service"]', function() {
        var val = $(this).find('option:selected').val();
        var text = $(this).find('option:selected').text();
        var id = $(this).data('card');
        if (val) {
            $('[data-id="' + id + '"').find('.name span').text(text);
            $(this).attr('data-id', val);
        }
    });
    // sortable videos
    $('.videos-accordion').sortable({
        handle: '.sortable-move',
        forcePlaceholderSize: true,
        placeholder: 'sortable-placeholder',
        beforeStop: function(event, ui) {
            $(".videos-accordion .card").each(function(i, el) {
                $(el).find('.sortable-input').val(i);
            });
        },
        create: function(event, ui) {
            $(".videos-accordion .card").each(function(i, el) {
                $(el).find('.sortable-input').val(i);
            });
        }
    });
    // remove video
    $('body').on('click', '.remove-video', function() {
        var id = $(this).attr('data-id');
        if ($(this).data('ajax')) {

            $.ajax({
                type: 'POST',
                url: URL + '/admin/ajax/delete/video',
                data: 'id=' + id,
                success: function(resp) {
                    $('.card-video[data-id="' + id + '"]').remove();
                    Snackbar.show({ text: __('Deletion is successful'), customClass: 'bg-success' });
                }
            });
        } else {
            $('.card-video[data-id="' + id + '"]').remove();
            Snackbar.show({ text: __('Deletion is successful'), customClass: 'bg-success' });
        }
    });
    // sortable actors
    $('.actors-accordion').sortable({
        handle: '.sortable-move',
        forcePlaceholderSize: true,
        placeholder: 'sortable-placeholder',
        beforeStop: function(event, ui) {
            $(".actors-accordion .card").each(function(i, el) {
                $(el).find('.sortable-input').val(i);
            });
        },
        create: function(event, ui) {
            $(".actors-accordion .card").each(function(i, el) {
                $(el).find('.sortable-input').val(i);
            });
        }
    });
    // remove video
    $('body').on('click', '.remove-actor', function() {
        var id = $(this).attr('data-id');
        if ($(this).data('ajax')) {

            $.ajax({
                type: 'POST',
                url: URL + '/admin/ajax/delete/actor',
                data: 'id=' + id,
                success: function(resp) {
                    $('.card-actor[data-id="' + id + '"]').remove();
                    Snackbar.show({ text: __('Deletion is successful'), customClass: 'bg-success' });
                }
            });
        } else {
            $('.card-actor[data-id="' + id + '"]').remove();
            Snackbar.show({ text: __('Deletion is successful'), customClass: 'bg-success' });
        }
    });

    $('body').on('click', '.btn-season', function() {

        var id = $(".seasons .card").last().data('id') + 1;
        if (id) {
            var id = id;
        } else {
            var id = 1;
        }
        var form = $('.modal-body');
        var detail = [
            { id: id, name: form.find('[name="season"]').val() }
        ];
        $('#card-season').tmpl(detail).appendTo('.seasons');
        $('.modal').modal('hide');
        $(".seasons .card").each(function(i, el) {
            $(el).find('.sortable-input').val(i);
        });
    });
    // remove season
    $('body').on('click', '.remove-season', function() {
        var id = $(this).attr('data-id');
        if ($(this).data('ajax')) {

            $.ajax({
                type: 'POST',
                url: URL + '/admin/ajax/delete/season',
                data: 'id=' + id,
                success: function(resp) {
                    $('.card-season[data-id="' + id + '"]').remove();
                    Snackbar.show({ text: __('Deletion is successful'), customClass: 'bg-success' });
                }
            });
        } else {
            $('.card-season[data-id="' + id + '"]').remove();
            Snackbar.show({ text: __('Deletion is successful'), customClass: 'bg-success' });
        }
    });
    // sortable videos
    $('.seasons').sortable({
        handle: '.sortable-move',
        forcePlaceholderSize: true,
        placeholder: 'sortable-placeholder',
        beforeStop: function(event, ui) {
            $(".seasons .card").each(function(i, el) {
                $(el).find('.sortable-input').val(i);
            });
        },
        create: function(event, ui) {
            $(".seasons .card").each(function(i, el) {
                $(el).find('.sortable-input').val(i);
            });
        }
    });
    // remove collection
    $('body').on('click', '.remove-collection', function() {
        var id = $(this).attr('data-id');
        if ($(this).data('ajax')) {

            $.ajax({
                type: 'POST',
                url: URL + '/admin/ajax/delete/collection',
                data: 'id=' + id,
                success: function(resp) {
                    $('.card-collection[data-id="' + id + '"]').remove();
                    Snackbar.show({ text: __('Deletion is successful'), customClass: 'bg-success' });
                }
            });
        } else {
            $('.card-collection[data-id="' + id + '"]').remove();
            Snackbar.show({ text: __('Deletion is successful'), customClass: 'bg-success' });
        }
    });

    // remove actor content
    $('body').on('click', '.remove-actor-video', function() {
        var id = $(this).attr('data-id');
        if ($(this).data('ajax')) {

            $.ajax({
                type: 'POST',
                url: URL + '/admin/ajax/delete/actor',
                data: 'id=' + id,
                success: function(resp) {
                    $('.card-actor[data-id="' + id + '"]').remove();
                    Snackbar.show({ text: __('Deletion is successful'), customClass: 'bg-success' });
                }
            });
        } else {
            $('.card-video[data-id="' + id + '"]').remove();
            Snackbar.show({ text: __('Deletion is successful'), customClass: 'bg-success' });
        }
    });

    // user avatar remove
    $('body').on('click', '.media-remove', function() {
        var id = $(this).attr('data-id');
        if (id) {

            $.ajax({
                type: 'POST',
                url: URL + '/admin/ajax/delete/avatar',
                data: 'id=' + id,
                success: function(resp) {
                    $('.media-select').css('background-image', '');
                    Snackbar.show({
                        text: __('Deletion is successful'),
                        customClass: 'bg-success'
                    });
                }
            });
        }
    });
})(jQuery);