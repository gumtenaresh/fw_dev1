/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(function($) {
    var open = false;
    $('.left_close').click(function () {
        $("#footerSlideButton").hide();
        $("#footerSlideButton1").show();
        if(open === false) {
            $('#footerSlideContent').animate({
                height:'300px'
            });
            $(this).css('backgroundPosition', 'bottom left');
            open = true;
        } else {
            $('#footerSlideContent').animate({
                height: '0px'
            });
            $(this).css('backgroundPosition', 'bottom right');
            open = false;
        }
    });

    $('#right_clear').click(function () {
        $("#footerSlideButton1").hide();
        $("#footerSlideButton").show();
        if(open === false) {
            $('#footerSlideContent').animate({
                height:'300px'
            });
            $(this).css('backgroundPosition', 'bottom left');
            open = true;
        } else {
            $('#footerSlideContent').animate({
                height: '0px'
            });
            $(this).css('backgroundPosition', 'bottom right');
            open = false;
        }
    });
    $('#clearHistory').click(function () {
        $.ajax({
            type: "POST",
            url:"{/literal}{$smarty.const.SITE_URL}{literal}fanwires/clearSession/",
            success: function(response) {
                window.location.reload(true);
            }
        });
    });
});
            