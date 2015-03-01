window.onload = function(){
    $(function($) {
        var tab = $('.nav'),
        offset = tab.offset();

        $(window).scroll(function () {
            if($(window).scrollTop() + $('.header').height() > offset.top) {
                tab.addClass('fixed');
            } else {
                tab.removeClass('fixed');
            }
        });
    });
}