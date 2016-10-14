<?php

if (!$this->hasConfig()) {

    $this->setConfig('css_dir', 'assets/theme/css/');
    $this->setConfig('scss_dir', 'assets/theme/sass/');
    $this->setConfig('js_dir', 'assets/theme/js/');
    $this->setConfig('minify_html', 1);
    $this->setConfig('minify_css', 1);
    $this->setConfig('minify_js', 1);

}