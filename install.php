<?php

if (!$this->hasConfig()) {

    $this->setConfig('css_dir', 'assets/css/');
    $this->setConfig('scss_dir', 'assets/sass/');
    $this->setConfig('js_dir', 'assets/js/');
    $this->setConfig('minify_html', 1);
    $this->setConfig('minify_css', 1);
    $this->setConfig('minify_js', 1);

}