<?php

foreach (glob(__DIR__ . "/Helpers/*.php") as $filename) {
    include $filename;
}

//new App\Classes\GutenbergInitClass();
//new App\Classes\MailFormsClass();
//new App\Controllers\FrontPageController();
//new App\Controllers\Admin\StaticStringsController();
//new App\Controllers\Admin\OptionsPageController();

new App\Classes\CreatedXmlClass();
