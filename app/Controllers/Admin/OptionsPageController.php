<?php

namespace App\Controllers\Admin;

class OptionsPageController
{
    public function __construct()
    {
        /**
         * Add acf options
         */
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page(array(
                'page_title' => __('Theme settings'),
            ));
        }
    }
}