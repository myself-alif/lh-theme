(function ($) {
    const initialize_block = function ($block) {
        // code goes here
        const blockId = $block[0].id;
        console.log(blockId);
        return $block;
    };

    $(function () {
        $('.lh-acf-block.lh-acf-block-title').each(function () {
            initialize_block($(this));
        });
    });

    // Initialize dynamic block preview (editor).
    if (window.acf) {
        window.acf.addAction(
            'render_block_preview/type=lh/title',
            initialize_block
        );
    }
})(jQuery);
