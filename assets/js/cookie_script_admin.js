(function() {

    jQuery(document).ready(function($) {

        var that,
            url,
            itemId = $('#cookie_script_item_id').val(),
            srcUrl = $('#cookie_script_item_src').val();

        $('#cookie_script_item_src').change('change keydown keyup click',  function() {

            that = $(this),
            itemId = $(this).val(),
            url = srcUrl;

            if (srcUrl != null) {

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(d, s, x) {
                        that.removeClass('CookieScript--error').addClass('CookieScript--success');
                        $('.CookieScript__adminForm--footer').find('span').text( cookie_script_admin_exchanger.notice_success );
                    },
                    error: function(d, s, x) {
                         that.removeClass('CookieScript--success').addClass('CookieScript--error');
                         $('.CookieScript__adminForm--footer').find('span').text( cookie_script_admin_exchanger.notice_error );
                    }
                });

            }

            that.removeClass('CookieScript--success').addClass('CookieScript--error');

        })

        //TODO need to remove this part of code on next update
        $('.CookieScript__connectionType').find('label').on('click', function() {

            that = $(this);

            switch ( that.find('input').val() ) {
                case '1':
                    url = 'http://chs03.cookie-script.com/s/' + itemId + '.js';
                    break;
                case '2':
                    url = 'https://cookie-script.com/s/' + itemId + '.js';
                    break;
                case '3':
                    url = 'https://eu.cookie-script.com/s/' + itemId + '.js';
                    break;
            }

            $.ajax({
                url: url,
                type: 'get',
                success: function(d, s, x) {
                    $('.CookieScript__connectionType').find('label').removeClass();
                    $('.CookieScript__connectionType').find('label').find('input').removeAttr('disabled');
                    that.removeClass('CookieScript--error').addClass('CookieScript--success');
                    that.find('input').attr('checked', true);
                    $('.CookieScript__adminForm--footer').find('span').text( cookie_script_admin_exchanger.notice_success );
                },
                error: function(d, s, x) {
                    if ( that.attr('for') == "cookie_script_item_connection_type_2" ) {
                        $('.CookieScript__adminForm--footer').find('span').html( cookie_script_admin_exchanger.notice_error_https );
                    } else if ( that.attr('for') == "cookie_script_item_connection_type_3" ) {
                        $('.CookieScript__adminForm--footer').find('span').html( cookie_script_admin_exchanger.notice_error_geo );
                    } else {
                        $('.CookieScript__adminForm--footer').find('span').text( cookie_script_admin_exchanger.notice_error );
                    }
                    $('.CookieScript__connectionType').find('label').removeClass();
                    $('.CookieScript__connectionType').find('label').find('input').removeAttr('disabled');
                    that.removeClass('CookieScript--success').addClass('CookieScript--error');
                    that.find('input').removeAttr('checked').attr('disabled', true);
                }
            });

        });

    });

})();
