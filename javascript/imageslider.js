(function ($) {
    $(document).ready(function () {
        if ($(window).width() > 640) {
            initVideoVisual();
        }

        // slickslider init
        initSlickslider();
    });
}(jQuery));

function initVideoVisual() {
    $('.video-visual').each(function () {
        $(this).YTPlayer({
            fitToBackground: true,
            videoId: $(this).data('id'),
            playbackQuality: 'hd1080',
            start: 60
        })
    });
}

function initSlickslider() {
    $('.slick-slider').slick({
        dots: true,
        infinite: true,
        prevArrow: '<button type="button" data-role="none" class="slick-prev" aria-label="Previous" tabindex="0" role="button">‹</button>',
        nextArrow: '<button type="button" data-role="none" class="slick-next" aria-label="Next" tabindex="0" role="button">›</button>',
        autoplay: true
    });
}