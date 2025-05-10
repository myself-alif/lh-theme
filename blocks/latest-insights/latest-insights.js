(function ($) {
    const initialize_block = function ($block) {
        const $selectedPosts = $block.find('.selected_post');

        $selectedPosts.each(function () {
            const $post = $(this);
            const $modal = $post.find('#modalWrapper');
            const $backdrop = $modal.find('.modal-backdrop');
            const $popup = $modal.find('.popup');

            const showModal = function () {
                if (!$modal.is(':visible')) {
                    $modal.fadeIn(0); // Instant show
                    $popup
                        .css({
                            opacity: 0,
                            transform: 'translate(-50%, -50%) scale(0.95)',
                        })
                        .animate(
                            { opacity: 1 },
                            {
                                duration: 300,
                                step: function (now, fx) {
                                    if (fx.prop === 'opacity') {
                                        const scale = 0.95 + 0.05 * now;
                                        $(this).css(
                                            'transform',
                                            `translate(-50%, -50%) scale(${scale})`
                                        );
                                    }
                                },
                            }
                        );

                    $({ blurValue: 0 }).animate(
                        { blurValue: 2 },
                        {
                            duration: 300,
                            step: function (now) {
                                $backdrop.css(
                                    'backdrop-filter',
                                    `blur(${now}px) brightness(${100 - now * 7.5}%)`
                                );
                            },
                        }
                    );
                }
            };

            const hideModal = function () {
                $popup.animate(
                    { opacity: 0 },
                    {
                        duration: 200,
                        step: function (now, fx) {
                            if (fx.prop === 'opacity') {
                                const scale = 1 - 0.05 * (1 - now);
                                $(this).css(
                                    'transform',
                                    `translate(-50%, -50%) scale(${scale})`
                                );
                            }
                        },
                    }
                );

                $({ blurValue: 2 }).animate(
                    { blurValue: 0 },
                    {
                        duration: 200,
                        step: function (now) {
                            $backdrop.css(
                                'backdrop-filter',
                                `blur(${now}px) brightness(${100 - now * 7.5}%)`
                            );
                        },
                        complete: function () {
                            $modal.fadeOut(100);
                        },
                    }
                );
            };

            // Desktop: hover to trigger modal
            $post.on('mouseenter', function () {
                if (window.innerWidth > 767) {
                    showModal();
                }
            });

            // Mobile/tablet: tap to trigger modal
            $post.on('click touchstart', function (e) {
                if (window.innerWidth <= 767) {
                    // Avoid double-triggering on touch/click
                    if (!$(e.target).closest('.popup, .modal-backdrop').length) {
                        showModal();
                    }
                }
            });

            // Close modal on icon click
            $post.on('click', '.close-modal, .modal_trigger', function (e) {
                e.stopPropagation();
                hideModal();
            });
        });

        return $block;
    };

    $(function () {
        $('.lh-acf-block.lh-acf-block-latest-insights').each(function () {
            initialize_block($(this));
        });
    });

    // For ACF block preview mode in editor
    if (window.acf) {
        window.acf.addAction(
            'render_block_preview/type=lh/latest-insights',
            initialize_block
        );
    }
})(jQuery);
