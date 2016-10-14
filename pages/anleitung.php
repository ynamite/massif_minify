<?php
$package = $this->getProperty('package') . '-';
//rex_addon::get('myaddon')->getProperty('author')

$content = '';

$setup = '<h3>.htaccess</h3>
            <p>' . $this->i18n('massif_minify_htaccess_note') . ':<br /><br />
            
            '.highlight_string('<IfModule mod_rewrite.c>
    # REWRITE RULE FOR JS/CSS VERSIONING
	RewriteRule ^(.*)\.\d{10}\.(css|js)$ $1.$2 [L]
</IfModule>', true).'
            </p>
            <h3>' . $this->i18n('massif_minify_usage_title') . '</h3>
            <p>' . $this->i18n('massif_minify_usage_note') . ':<br /><br />

            '.highlight_string('massif_minify::getCSSFile("style.css")?>" />', true).'<br />Bindet eine CSS/SCSS Datei ein<br /><br />
            '.highlight_string('massif_minify::getCSSMinFile("style.css")?>" />', true).'<br />Komprimiert und bindet eine CSS/SCSS Datei ein<br /><br />
            '.highlight_string('massif_minify::getCombinedCSSFile("style.css", array("normalize.css", "layout.css"))?>" />', true).'<br />Fügt mehrere CSS Dateien zu einer zusammen, in diesem Fall style.css, und bindet die Datei ein<br /><br />
            '.highlight_string('massif_minify::getCombinedCSSMinFile("style.css", array("normalize.css", "layout.css"))?>" />', true).'<br />Fügt mehrere CSS Dateien zu einer zusammen, in diesem Fall style.css, komprimiert und bindet die Datei ein<br /><br />
            '.highlight_string('massif_minify::getJSFile("script.js")?>" />', true).'<br />Bindet eine JS Datei ein<br /><br />
            '.highlight_string('massif_minify::getJSMinFile("script.js")?>" />', true).'<br />Komprimiert und bindet eine JS Datei ein<br /><br />
            '.highlight_string('massif_minify::getCombinedJSFile("scripts.js", array("jquery.js", "plugin.js"))?>" />', true).'<br />Fügt mehrere JS Dateien zu einer zusammen, in diesem Fall scripts.js, und bindet die Datei ein<br /><br />
            '.highlight_string('massif_minify::getCombinedJSMinFile("scripts.js", array("jquery.js", "plugin.js"))?>" />', true).'<br />Fügt mehrere JS Dateien zu einer zusammen, in diesem Fall scripts.js, komprimiert und bindet die Datei ein<br /><br />
            '.highlight_string('massif_minify::getJSCodeFromFile("relativer/pfad/zur/datei", $compress BOOLEAN default true)?>" />', true).'<br />Holt JS-Code aus einer Datei, komprimiert oder nicht<br /><br />
            '.highlight_string('massif_minify::getJSCodeFromTemplate($templateId INT, $compress BOOLEAN default true)?>" />', true).'<br />Holt JS-Code aus einem Template, komprimiert oder nicht<br /><br />
            '.highlight_string('massif_minify::getResourceFile("image.jpg")?>" />', true).'<br />Bindet eine beliebige Resource ein<br /><br />
            
            <b>' . $this->i18n('massif_minify_example') . ' CSS</b><br />
            '.highlight_string('<link rel="stylesheet" href="<?=massif_minify::getCSSFile("style.scss")?>" />', true).'<br /><br />

            <b>' . $this->i18n('massif_minify_example') . ' SCSS</b><br />
            '.highlight_string('<link rel="stylesheet" href="<?=massif_minify::getCSSFile("style.scss")?>" />', true).'<br /><br />
            
             <b>' . $this->i18n('massif_minify_example') . ' JS</b><br />
            '.highlight_string('<script src="<?=massif_minify::getJSFile("scripts.js")?>"></script>', true).'
            
           </p>';

$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('massif_minify_guide'));
$fragment->setVar('body', $setup, false);
echo $fragment->parse('core/page/section.php');
    
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