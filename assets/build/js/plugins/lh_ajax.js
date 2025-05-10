class Lh_Ajax {
    constructor (args) {
        if (!jQuery || !args.block.length) {
            return false;
        }

        this.$block = args.block;
        this.$posts_container = this.$block.find(args.post_container);
        this.$load_more_btn = this.$block.find(args.load_more_btn);
        this.$pagination_container = this.$block.find(args.pagination_container);
        this.current_filter_id = this.$block.data('postsCategory');
        this.base_url = this.$block.data('baseUrl') ? this.$block.data('baseUrl').replace(/\/$/, '') : '';
        this.buttons_active_style = 'buttons-active';
        this.ajax_loading_style = 'lh-ajax-loading';

        this.init();
    }

    init () {
        this.$block.addClass('buttons-active');
    }

    /**
     * update_posts_by_filter
     *
     * Replace posts in post list with new category,
     * this now takes a carousel object and a callback function, some of this depends on specific html structure for slides
     *
     * @param {int}         category_id
     * @param {bool}        update_url
     * @param {$carousel}   carousel
     * @param {string}      ajax_call
     * @param {func}        callback
     */
    update_posts_by_filter (category_id = false, update_url = true, carousel = false, ajax_call = 'lh_get_posts_listing', callback = false) {
        const self = this;

        self.current_filter_id = category_id;

        self.args = {
            postsCategory: self.current_filter_id,
            postsPage: 1,
        };

        self.add_active_classes();
        self.update_block_data_attributes(self.args);

        if (update_url) {
            self.update_address_bar(self.args);
        }

        jQuery.ajax({
            url: waktools.wp.ajax_url,
            method: 'POST',
            data: self.return_data_attributes_object(ajax_call),
            beforeSend: () => {
                self.$posts_container.prop('disabled', true);
                self.$posts_container.block({ message: null });
            },
        }).done(function (response) {
            if (response.status === 'success' && response.posts) {
                if (!carousel) {
                    self.$posts_container.empty();
                    jQuery(response.posts).appendTo(self.$posts_container);

                    if (self.$pagination_container && response.pagination) {
                        self.$pagination_container.empty();
                        jQuery(response.pagination).appendTo(self.$pagination_container);
                    }

                    if (self.$load_more_btn && response.show_more !== false) {
                        self.$load_more_btn.data('page', response.show_more);
                        self.$load_more_btn.attr('data-page', response.show_more);
                    } else {
                        self.$load_more_btn.parent().remove();
                    }
                } else {
                    const $postObjects = jQuery('<div></div>').append(jQuery(response.posts));

                    carousel.remove('.lh-slide');

                    $postObjects.find('li').each(function () {
                        carousel.add(this);
                    });

                    carousel.go('+1');
                }

                // remove element blocking
                self.remove_active_classes();
            }
        }).then(() => {
            if (callback && typeof callback === 'function') {
                callback();
            }
        }).always(() => {
            self.$posts_container.unblock({ fadeOut: 0 });
            self.$posts_container.prop('disabled', false);
        });
    }

    /**
     * update_posts_by_pagination
     *
     * Append new page to post list
     * now includes target functionality from update_posts_by_pagination_only function
     *
     * @param {int}         page
     * @param {int}         category_id
     * @param {$carousel}   carousel
     * @param {string}      ajax_call
     * @param {func}        callback
     */
    update_posts_by_pagination (page, category_id, carousel = false, ajax_call = 'lh_get_posts_listing', callback = false) {
        const self = this;

        self.args = {
            postsCategory: category_id,
            postsPage: page,
        };

        self.add_active_classes();
        self.update_block_data_attributes(self.args);

        if (carousel) {
            self.update_address_bar({ postsPage: page });
        } else {
            self.update_address_bar(self.args);
        }

        jQuery.ajax({
            url: waktools.wp.ajax_url,
            method: 'POST',
            data: self.return_data_attributes_object(ajax_call),
            beforeSend: () => {
                self.$posts_container.prop('disabled', true);
                self.$posts_container.block({ message: null });
            },
        }).done(function (response) {
            if (response.status === 'success' && response.posts) {
                if (carousel) {
                    const $postObjects = jQuery('<div></div>').append(jQuery(response.posts));

                    $postObjects.find('li').each(function () {
                        carousel.add(this);
                    });

                    carousel.go('+1');
                } else {
                    self.$posts_container.empty();
                    jQuery(response.posts).appendTo(self.$posts_container);

                    if (self.$pagination_container && response.pagination) {
                        self.$pagination_container.empty();
                        jQuery(response.pagination).appendTo(self.$pagination_container);
                    }
                }

                self.remove_active_classes();
            }
        }).then(() => {
            if (callback && typeof callback === 'function') {
                callback();
            }
        }).always(() => {
            self.$posts_container.unblock({ fadeOut: 0 });
            self.$posts_container.prop('disabled', false);
        });
    }

    /**
     * update_posts_by_more
     *
     * Add posts to the list by clicking load more
     *
     * @param {int}         page
     * @param {int}         category_id
     * @param {$carousel}   target // this used to be 'desk' or $carousel, now false or $carousel
     * @param {bool}        update_address
     * @param {string}      ajax_call
     * @param {func}        callback
     */
    update_posts_by_more (page, category_id, $element, target = false, update_address = true, ajax_call = 'lh_get_posts_listing', callback = false) {
        const self = this;

        self.args = {
            postsCategory: category_id,
            postsPage: page,
        };

        self.add_active_classes();
        self.update_block_data_attributes(self.args);

        if (update_address) {
            self.update_address_bar({ postsPage: page });
        }

        jQuery.ajax({
            url: waktools.wp.ajax_url,
            method: 'POST',
            data: self.return_data_attributes_object(ajax_call),
            beforeSend: () => {
                $element.prop('disabled', true);
                $element.block({ message: null });
            },
        }).done(function (response) {
            if (response.status === 'success') {
                if (response.posts) {
                    if (response.show_more !== false) {
                        $element.data('page', response.show_more);
                        $element.attr('data-page', response.show_more);
                    } else {
                        $element.parent().remove();
                    }

                    self.remove_active_classes();

                    if (!target) {
                        jQuery(response.posts).appendTo(self.$posts_container);
                    } else {
                        const $postObjects = jQuery('<div></div>').append(jQuery(response.posts));

                        $postObjects.find('li').each(function () {
                            target.add(this);
                        });

                        target.go('+1');
                    }
                }
            }

            if (response.status === 'error') {
                $element.parent().remove();
            }
        }).then(() => {
            if (callback && typeof callback === 'function') {
                callback();
            }
        }).always(() => {
            $element.unblock({ fadeOut: 0 });
            $element.prop('disabled', false);
        });
    }

    /**
     * search_for_string_with_input
     *
     * Get search results, this could be improved more however it is unlikely to be used multiple times in one project so
     * array included to decouple it from static elements
     *
     *
     * @param {obj}     $element
     * @param {str}     search_string
     * @param {string}  ajax_call
     * @param {array}   element_list {"search_results_container":".str","pagainated_list":"#str","input":"","input_container":""}
     * @param {func}    callback
     */
    search_for_string_with_input (
        $element = false,
        search_string = false,
        ajax_call = 'lh_get_contributors_by_search_string',
        element_list = {
            search_results_container: '.lh-search-results',
            pagainated_list: '.lh-pagination-and-list',
            input: '#search-input',
            input_container: '.lh-input-container'
        },
        callback = false,
    ) {
        if (!search_string || !$element) {
            return false;
        }

        const self = this;
        const $search_results_container = $element.find(element_list.search_results_container);
        const $paginated_list = $element.find(element_list.pagainated_list);
        const $input = $element.find(element_list.input);
        const $input_container = $element.find(element_list.input_container);

        self.add_active_classes();

        jQuery.ajax({
            url: waktools.wp.ajax_url,
            method: 'POST',
            data: {
                action: ajax_call,
                search_string: search_string
            },
            beforeSend: () => {
                $element.prop('disabled', true);
                $element.block({ message: null });
            },
        }).done(function (response) {
            if (response.status === 'success') {
                if (response.posts) {
                    self.remove_active_classes();
                    $search_results_container.empty().show();
                    $paginated_list.hide();
                    jQuery(response.posts).appendTo($search_results_container);
                }
            }

            if (response.status === 'error') {
                $search_results_container.hide();
                $paginated_list.show();
            }
        }).always(() => {
            $input.prop('disabled', false);
            $input_container.unblock({ fadeOut: 0 });
        }).then(() => {
            if (callback) {
                callback();
            }
        });
    }

    /**
     * Get all the data and action for ajax call
     * @param {str}     action
     * @param {$}       $target_element // target to get query params from
     * @return {object}
     */
    return_data_attributes_object (action, $target_element = false) {
        let $target = this.$block;

        if ($target_element.length) {
            $target = $target_element;
        }

        const return_array = {
            action,
            base_url: $target.data('baseUrl'),
            post_type: $target.data('postsType'),
            posts_category: $target.data('postsCategory'),
            offset: $target.data('postsOffset'),
            posts_per_page: $target.data('postsPerPage'),
            posts_page: $target.data('postsPage'),
            role: $target.data('role'),
            page_id: $target.data('pageId'),
            order_by: $target.data('orderBy'),
            included_ids: $target.data('postsIncluded'),
            featured_ids: $target.data('postsFeatured'),
            excluded_ids: $target.data('postsExcluded'),
            taxonomy_name: $target.data('postsTaxonomyName'),
            posts_max_page: $target.data('postsMaxPage'),
            first_pages_count: $target.data('firstPagesCount'),
            last_pages_count: $target.data('lastPagesCount'),
            around_current_page: $target.data('aroundCurrentPage'),
            display_all_count: $target.data('displayAllCount'),
            template_name: $target.data('templateName'),
            block_name: $target.data('blockName'),
            custom_meta: $target.attr('data-custom-meta'),
            tax_query: $target.attr('data-tax-query'),
            return_fields: $target.data('returnFields'),
            dest_id: $target.data('destinationId')
        };

        return return_array;
    }

    /**
     * Add loading and active classes from container
     *
     */
    add_active_classes () {
        this.$block.addClass(this.ajax_loading_style);
        // this.$block.removeClass(this.buttons_active_style);
    }

    /**
     * Removing loading and active classes from container
     *
     */
    remove_active_classes () {
        // this.$block.addClass(this.buttons_active_style);
        this.$block.removeClass(this.ajax_loading_style);
    }

    /**
     * Take a list of data refs and numbers and update where necesasary
     *
     * @param {obj} args
     */
    update_block_data_attributes (args) {
        if (args) {
            for (const key in args) {
                const value = args[key];
                this.$block.data(key, value);
            }
        }
    }

    /**
     * Update the address bar to reflect the current state
     *
     * @param {array} args {"page_num": int, "category": int}
     */
    update_address_bar (args) {
        const category = args.postsCategory || 0;
        const page_num = args.postsPage || 1;
        const country = args.country || 0;
        let url_string = '';

        if (category !== 0) {
            url_string = 'category/' + category + '/';
        }

        if (page_num > 1) {
            url_string += 'page/' + page_num + '/';
        }

        if (country.length) {
            url_string += 'country/' + country + '/';
        }

        // console.log(this.base_url);

        history.pushState({}, null, this.base_url + '/' + url_string);
    }
}

window.waktools.functions.lh_register_class(Lh_Ajax);
