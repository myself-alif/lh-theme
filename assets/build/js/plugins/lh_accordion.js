/**
 * class Lh_Accordion
 *
 * @params args obj ($block at least)
 *
 */
class Lh_Accordion {
    constructor (args) {
        if (!jQuery || !args.$block.length) {
            return false;
        }

        this.$block = args.$block;
        this.header_id = args.header_id || '#header';
        this.toggled_class = args.toggled_class || 'open';
        this.items_container_class = args.items_container_class || '.lh-accordion';
        this.item_class = args.item_class || '.lh-accordion-item:not(.lh-no-copy)';
        this.item_header_class = args.item_header_class || '.lh-accordion-item-header';
        this.scroll_anim_time = args.scroll_anim_time || 300;
        this.breakpoint = args.breakpoint || 768;

        this.$accordion_items_container = args.$block.find(this.items_container_class);
        this.$accordion_items = args.$block.find(this.item_class);

        this.init();
    }

    init () {
        this.set_event_listeners();
    }

    set_event_listeners () {
        const self = this;

        if (this.$accordion_items.length > 0) {
            this.$accordion_items.each(function () {
                const $item = jQuery(this);
                const $item_header = $item.find(self.item_header_class);

                $item_header.on('click', function () {
                    self.toggle_open($item);
                });
            });
        }
    }

    toggle_open ($item) {
        this.$accordion_items_container.css('height', this.$accordion_items_container.height());

        if ($item.hasClass(this.toggled_class)) {
            $item.removeClass(this.toggled_class);
        } else {
            this.$accordion_items.removeClass(this.toggled_class);
            $item.addClass(this.toggled_class);

            const item_position = $item.offset().top - jQuery(this.header_id).height();

            if ((window.scrollY > item_position) && (jQuery(window).width() < this.breakpoint)) {
                jQuery('html, body').animate({ scrollTop: item_position }, this.scroll_anim_time);
            }
        }

        setTimeout(() => {
            const current_height = this.$accordion_items_container.height();
            const auto_height = this.$accordion_items_container.css('height', 'auto').height();

            this.$accordion_items_container.height(current_height).animate({ height: auto_height }, 500);
        }, 500);
    }
}

window.waktools.functions.lh_register_class(Lh_Accordion);
