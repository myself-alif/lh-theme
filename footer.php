<?php if (!defined('ABSPATH')) { header('Location: /'); exit; } ?>

            <footer id="footer">
                <?php if (is_active_sidebar('footer_sidebar')) { ?>
                    <div class="lh-footer-sidebar">
                        <?php dynamic_sidebar('footer_sidebar'); ?>
                    </div>
                <?php } ?>
            </footer>
        </div><!-- end: #root -->

        <?php wp_footer(); ?>
    </body>
</html>