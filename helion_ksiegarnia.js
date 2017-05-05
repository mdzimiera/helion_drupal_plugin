jQuery(document).ready(function() {
    jQuery('.hk_books_hidden_href_1').click(function() {
        jQuery(this).fadeOut();
        jQuery('.hk_books_hidden_1').slideDown('slow');
        return false;
    });
    jQuery('.hk_books_hidden_href_2').click(function() {
        jQuery(this).fadeOut();
        jQuery('.hk_books_hidden_2').slideDown('slow');
        return false;
    });
 
    jQuery('#helion_ksiegarnia_wyszukiwarka_submit').click(function() {
        
        if (jQuery("#helion_ksiegarnia_wyszukiwarka_text").val().length >= 3)
                location.replace(jQuery('#helion_ksiegarnia_wyszukiwarka_url').val() + '/' + 'szukaj-' + jQuery('#helion_ksiegarnia_wyszukiwarka_text').val());
        else {
            alert("Dopuszczalna minimalna ilość znaków to 3.");
            jQuery("#helion_ksiegarnia_wyszukiwarka_text").focus();
        }
    });

    jQuery("#helion_ksiegarnia_wyszukiwarka_text").keypress(function(e) {
        if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
            jQuery('#helion_ksiegarnia_wyszukiwarka_submit').click();
            return false;
        } else {
            return true;
        }
    });

    jQuery(".pager > .item > a").click(function() {
        jQuery(".pager > .item").removeClass('hk_checked_box');
        jQuery(this).parent().addClass('hk_checked_box');
        var id = jQuery(this).attr('id').replace(/[^0-9]+/i, '');
        jQuery('.hk-page-per-page').fadeOut('fast');
        jQuery('.hk-page-per-page').eq(id).fadeIn(1000);
        return false;
    });

    var hk_href = location.href;
    var hk_id = 0;
    if (hk_href.indexOf('#') != -1)
        hk_id = hk_href.split("#")[1] - 1;

    if (hk_id == -1)
        hk_id = 0;
    jQuery(".pager > .item").eq(hk_id).addClass('hk_checked_box');
    if (hk_id > 0)
        jQuery('.hk-page-per-page').eq(0).css('display', 'none');
    jQuery('.hk-page-per-page').eq(hk_id).css('display', 'block');
});