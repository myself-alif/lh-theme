<?php

namespace LH_THEME\Plugins_Integrations;

class Gravity_Forms_Integration
{
    /**
	 * Setup action & filter hooks
	 * 
	 * @return Gravity_Forms_Integration
	 */
	public function __construct() {
        if ($this->is_plugin_active()) {
            add_filter('gform_submit_button', [$this, 'input_to_button'], 10, 2);
        } else {
            add_action('admin_notices', [$this, 'show_gf_admin_notice']);
            return;
        }
    }

    /**
     * Filters the buttons.
     * Replaces the form's <input> buttons with <button> while maintaining attributes from original <input>.
     * 
     * @param string $button Contains the <input> tag to be filtered.
     * @param object $form   Contains all the properties of the current form.
     * 
     * @return string The filtered button.
     */
     function input_to_button($button, $form) {
        $dom = new \DOMDocument();

        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $button);

        $input      = $dom->getElementsByTagName('input')->item(0);
        $new_button = $dom->createElement('button');

        $new_button->appendChild($dom->createTextNode($input->getAttribute('value')));
        $input->removeAttribute('value');

        foreach( $input->attributes as $attribute ) {
            $new_button->setAttribute( $attribute->name, $attribute->value );
        }

        // add our classes, needs to be extended if we update hooks
        $classes_to_add = 'lh-btn lh-btn-primary lh-buttons-standard-text';
        $inputClasses   = $input->getAttribute('class') . ' ' . $classes_to_add;

        $new_button->setAttribute('class', $inputClasses);

        $input->parentNode->replaceChild($new_button, $input);

        return $dom->saveHtml($new_button);
    }

    /**
     * Check if Gravity Forms plugin is active
     *
     * @return Boolean
     */
    private function is_plugin_active() {
        return class_exists('GFForms');
    }

    /**
     * Show admin notice when Gravity Forms is not installed/activated
     *
     * @return Void
     */
    public function show_gf_admin_notice() {
        echo '
        <div class="updated">
            <p>
            ' . sprintf(
                    __('<strong>%s</strong> requires <strong><a href="https://www.gravityforms.com/" target="_blank">Gravity Forms</a></strong> plugin to be installed and activated on your site.',
                    LH_THEME_SLUG),
                    __CLASS__
                ) . '
            </p>
        </div>';
    }
}