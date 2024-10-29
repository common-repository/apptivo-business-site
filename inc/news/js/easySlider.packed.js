/*
 * 	Easy Slider - jQuery plugin
 *	written by Alen Grakalic	
 *	http://cssglobe.com/post/3783/jquery-plugin-easy-image-or-content-slider
 *
 *	Copyright (c) 2009 Alen Grakalic (http://cssglobe.com)
 *	Dual licensed under the MIT (MIT-LICENSE.txt)
 *	and GPL (GPL-LICENSE.txt) licenses.
 *
 *	Built for jQuery library
 *	http://jquery.com
 */
(function($) {
    $.fn.easySlider = function(d) {
        var e = {
            prevId: 'prevBtn',
            prevText: 'Previous',
            nextId: 'nextBtn',
            nextText: 'Next',
            orientation: '',
            speed: 800
        };
        var d = $.extend(e, d);
        return this.each(function() {
            obj = $(this);
            var s = $("li", obj).length;
            var w = obj.width();
            var h = obj.height();
            var b = s - 1;
            var t = 0;
            var c = (d.orientation == 'vertical');
            $("ul", obj).css('width', s * w);
            if (!c) $("li", obj).css('float', 'left');
            $(obj).after('<span id="' + d.prevId + '"><a href=\"javascript:void(0);\">' + d.prevText + '</a></span> <span id="' + d.nextId + '"><a href=\"javascript:void(0);\">' + d.nextText + '</a></span>');
            $("a", "#" + d.prevId).hide();
            $("a", "#" + d.nextId).hide();
            $("a", "#" + d.nextId).click(function() {
                animate("next");
                if (t >= b) $(this).fadeOut();
                $("a", "#" + d.prevId).fadeIn()
            });
            $("a", "#" + d.prevId).click(function() {
                animate("prev");
                if (t <= 0) $(this).fadeOut();
                $("a", "#" + d.nextId).fadeIn()
            });

            function animate(a) {
                if (a == "next") {
                    t = (t >= b) ? b : t + 1
                } else {
                    t = (t <= 0) ? 0 : t - 1
                };
                if (!c) {
                    p = (t * w * -1);
                    $("ul", obj).animate({
                        marginLeft: p
                    }, d.speed)
                } else {
                    p = (t * h * -1);
                    $("ul", obj).animate({
                        marginTop: p
                    }, d.speed)
                }
            };
            if (s > 1) $("a", "#" + d.nextId).fadeIn()
        })
    }
})(jQuery);
