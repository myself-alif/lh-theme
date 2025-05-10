const main = (function ($) {
    jQuery.event.special.touchstart = {
        setup: function (_, ns, handle) {
            this.addEventListener('touchstart', handle, { passive: !ns.includes('noPreventDefault') });
        }
    };
    jQuery.event.special.touchmove = {
        setup: function (_, ns, handle) {
            this.addEventListener('touchmove', handle, { passive: !ns.includes('noPreventDefault') });
        }
    };
    jQuery.event.special.wheel = {
        setup: function (_, ns, handle) {
            this.addEventListener('wheel', handle, { passive: true });
        }
    };
    jQuery.event.special.mousewheel = {
        setup: function (_, ns, handle) {
            this.addEventListener('mousewheel', handle, { passive: true });
        }
    };

    $(function () {
        const $body = $('body');

        $(window).on('scroll', function () {
            set_header_classes_based_on_scroll_position();
        });

        $(window).resize(function () {
            if ($(window).width() > 768) {
                $body.removeClass('nav-open drawer-open');
                $('.lh-has-menu-drawer, .menu-item-has-children').removeClass('open');
                $body.removeClass('scrolling-blocked');
            }
        });

        function set_header_classes_based_on_scroll_position () {
            const scroll_top = $(window).scrollTop();

            if (scroll_top > 100) {
                $('#header').addClass('not-at-top');
                $body.addClass('not-at-top');
            } else {
                $('#header').removeClass('not-at-top');
                $body.removeClass('not-at-top');
            }
        }

        set_header_classes_based_on_scroll_position();

        $body.on('click', '.lh-share-btn', (e) => {
            e.stopPropagation();

            const $button = $(e.target);

            $button.toggleClass('is-open');
        });

        $body.on('click', '.lh-share-link.lh-share-copy', (e) => {
            const $button = $(e.target);
            const $temp = $('<input>');

            $body.append($temp);
            $temp.val(window.location.href).select();
            document.execCommand('copy');
            $temp.remove();

            $button.parents('.lh-share-link-group').find('.lh-share-copy-link-notification').addClass('show-notification');

            setTimeout(() => {
                $button.parents('.lh-share-link-group').find('.lh-share-copy-link-notification').removeClass('show-notification');
            }, 2000);
        });
    });
})(jQuery);

export { main as default };
