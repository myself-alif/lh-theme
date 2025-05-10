const gravity_forms = (function ($) {
    $(function () {
        $('.gform_wrapper').each(function () {
            const $gravity_form = $(this);

            $gravity_form.find('.gform_body .gform_fields .gfield input, .gform_body .gform_fields .gfield select, .gform_body .gform_fields .gfield textarea').each(function () {
                const $this = $(this);

                if ($this.val() !== '') {
                    $this.parents('.gfield').addClass('has-value');
                } else {
                    $this.parents('.gfield').removeClass('has-value');
                }
            });

            $gravity_form.on('focusin', '.gform_body .gform_fields .gfield input, .gform_body .gform_fields .gfield select, .gform_body .gform_fields .gfield textarea', function () {
                $(this).parents('.gfield').addClass('has-focus');
            });

            $gravity_form.on('focusout', '.gform_body .gform_fields .gfield input, .gform_body .gform_fields .gfield select, .gform_body .gform_fields .gfield textarea', function () {
                const $this = $(this);
                $this.parents('.gfield').removeClass('has-focus');

                if ($this.val() !== '') {
                    $this.parents('.gfield').addClass('has-value');
                }
            });

            $gravity_form.on('change input copy cut paste drag drop', '.gform_body .gform_fields .gfield input, .gform_body .gform_fields .gfield select, .gform_body .gform_fields .gfield textarea', function () {
                const $this = $(this);

                $this.parents('.gfield').addClass('was-changed');

                if ($this.val() !== '') {
                    $this.parents('.gfield').addClass('has-value');
                } else {
                    $this.parents('.gfield').removeClass('has-value');
                }
            });
        });
    });
})(jQuery);

export { gravity_forms as default };
