<?php
$package = $this->getProperty('package') . '-';
//rex_addon::get('myaddon')->getProperty('author')

$content = '';

if (rex_post('config-submit', 'boolean')) {
    $this->setConfig(rex_post('config', [
        ['css_dir', 'string'],
        ['scss_dir', 'string'],
        ['scss_css_output_dir', 'string'],
        ['js_dir', 'string'],
        ['js_output_dir', 'string'],
        ['minify_html', 'bool'],
        ['minify_css', 'bool'],
        ['minify_js', 'bool'],
        ['minify_single_line', 'bool'],
        ['absolute_paths', 'bool'],
    ]));

    echo rex_view::success($this->i18n('massif_minify_saved'));
}

$content = '<fieldset id="'.$package.'form">';

$formElements = [];

$n = [];
$n['label'] = '<label for="' . $package . 'css_dir">' . $this->i18n('massif_minify_css_dir') . '</label>';
$n['field'] = '<input class="form-control" type="text" id="' . $package . 'css_dir" name="config[css_dir]" value="' . $this->getConfig('css_dir') . '" />';
$n['note'] = rex_i18n::rawMsg('massif_minify_path_scheme_css', rex_url::backendPage('packages', ['subpage' => 'help', 'package' => $this->getPackageId()]));
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="' . $package . 'css_dir">' . $this->i18n('massif_minify_scss_dir') . '</label>';
$n['field'] = '<input class="form-control" type="text" id="' . $package . 'scss_dir" name="config[scss_dir]" value="' . $this->getConfig('scss_dir') . '" />';
$n['note'] = rex_i18n::rawMsg('massif_minify_path_scheme_scss', rex_url::backendPage('packages', ['subpage' => 'help', 'package' => $this->getPackageId()])) ;
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="' . $package . 'scss_css_output_dir">' . $this->i18n('massif_minify_scss_css_output_dir') . '</label>';
$n['field'] = '<input class="form-control" type="text" id="' . $package . 'scss_css_output_dir" name="config[scss_css_output_dir]" value="' . $this->getConfig('scss_css_output_dir') . '" />';
$n['note'] = rex_i18n::rawMsg('massif_minify_scss_css_output_dir_note', rex_url::backendPage('packages', ['subpage' => 'help', 'package' => $this->getPackageId()])) . '<br />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="' . $package . 'js_dir">' . $this->i18n('massif_minify_js_dir') . '</label>';
$n['field'] = '<input class="form-control" type="text" id="' . $package . 'js_dir" name="config[js_dir]" value="' . $this->getConfig('js_dir') . '" />';
$n['note'] = rex_i18n::rawMsg('massif_minify_path_scheme_js', rex_url::backendPage('packages', ['subpage' => 'help', 'package' => $this->getPackageId()]));
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="' . $package . 'js_output_dir">' . $this->i18n('massif_minify_js_output_dir') . '</label>';
$n['field'] = '<input class="form-control" type="text" id="' . $package . 'js_output_dir" name="config[js_output_dir]" value="' . $this->getConfig('js_output_dir') . '" />';
$n['note'] = rex_i18n::rawMsg('massif_minify_js_output_dir_note', rex_url::backendPage('packages', ['subpage' => 'help', 'package' => $this->getPackageId()])) . '<br />';
$formElements[] = $n;

$n = [];
$n['label'] = '<p style="margin: 0">' . $this->i18n('massif_minify_options') . '</p>';
$formElements[] = $n;


$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/form.php');

$formElements = [];

$n = [];
$n['label'] = '<label for="' . $package . 'minify_html">' . $this->i18n('massif_minify_minify_html') . '</label>';
$n['before'] = '<input type="checkbox" id="' . $package . 'minify_html" name="config[minify_html]" value="1" ' . ($this->getConfig('minify_html') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="' . $package . 'minify_css">' . $this->i18n('massif_minify_minify_css') . '</label>';
$n['before'] = '<input type="checkbox" id="' . $package . 'minify_css" name="config[minify_css]" value="1" ' . ($this->getConfig('minify_css') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="' . $package . 'minify_js">' . $this->i18n('massif_minify_minify_js') . '</label><br /><br />';
$n['before'] = '<input type="checkbox" id="' . $package . 'minify_js" name="config[minify_js]" value="1" ' . ($this->getConfig('minify_js') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="' . $package . 'minify_single_line">' . $this->i18n('massif_minify_single_line') . '</label><p class="help-block rex-note">'.rex_i18n::rawMsg('massif_minify_single_line_note', rex_url::backendPage('packages', ['subpage' => 'help', 'package' => $this->getPackageId()])).'</p>';
$n['before'] = '<input type="checkbox" id="' . $package . 'minify_single_line" name="config[minify_single_line]" value="1" ' . ($this->getConfig('minify_single_line') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="' . $package . 'absolute_paths">' . $this->i18n('absolute_paths') . '</label><br /><br />';
$n['before'] = '<input type="checkbox" id="' . $package . 'absolute_paths" name="config[absolute_paths]" value="1" ' . ($this->getConfig('absolute_paths') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;


$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/checkbox.php');

$content.= '</fieldset>';

$formElements = [];


$n = [];
$n['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="config-submit" value="1" ' . rex::getAccesskey($this->i18n('massif_minify_save'), 'save') . '>' . $this->i18n('massif_minify_save') . '</button>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('flush', true);
$fragment->setVar('elements', $formElements, false);
$buttons = $fragment->parse('core/form/submit.php');

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit');
$fragment->setVar('title', $this->i18n('massif_minify_setting'));
$fragment->setVar('body', $content, false);
$fragment->setVar('buttons', $buttons, false);
$content = $fragment->parse('core/page/section.php');


echo '
    <form action="' . rex_url::currentBackendPage() . '" method="post">
        ' . $content . '
    </form>';
    
    
?>
<style>
#<?=$package?>form .checkbox label {
    font-weight: normal;
    padding-left: 0;
}

#<?=$package?>form input[type=checkbox] {
    display: none;
}

#<?=$package?>form input[type=checkbox] + div label {
	position: relative;
}

#<?=$package?>form input[type=checkbox] + div label:before {
    font-family: FontAwesome;
    font-size: 20px;
    width: 30px;
    text-align: center;
    border-radius: 3px;
    background: #E9ECF2;
    border: 1px solid #c3c9d4;
    display: inline-block;
    margin-right: 10px;
}

#<?=$package?>form input[type=checkbox] + div label:before {
    padding-left: 2px;
    color: #c3c9d4;
    content: "\f00d";
}

#<?=$package?>form input[type=checkbox] + div label:before {
}

#<?=$package?>form input[type=checkbox]:checked + div label:before {
    padding-left: 2px;
    color: #3CB594;
    content: "\f00c";
}

#<?=$package?>form input[type=checkbox]:checked + div label:before {
}
</style>