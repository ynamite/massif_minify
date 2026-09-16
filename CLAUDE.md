# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

`massif_minify` is a REDAXO 5 addon (REDAXO `>=5.9, <6`, requires `be_style >=2.9, <4`). It combines/minifies CSS, SCSS and JS files for frontend templates, adds mtime-based cache-busting to asset URLs, and optionally minifies the whole HTML output. Ported from Starlord's seo42 `resource_includer`. Backend UI and README are in German.

There is no build, test, lint or package-manager setup. Vendors are committed, not composer-installed. Tooling: PHP only. Sanity check a change with `php -l lib/class.massif_minify.php`. To test behaviour, the addon has to sit in a REDAXO install under `redaxo/src/addons/massif_minify/`.

## Architecture

Everything runs through one static class, `massif_minify` in `lib/class.massif_minify.php`:

- `boot.php` registers `massif_minify::init` on `PACKAGES_INCLUDED`. `init()` reads addon config into static props (source dirs, optional separate output dirs falling back to source dirs, minify flags, `absolute_paths`) and, if `minify_html` is on and we are not in the backend / a search_it index run, registers `minifyHTML` on `OUTPUT_FILTER` (LATE).
- The class is wrapped in `if (class_exists(ScssPhp\ScssPhp\Formatter\Compressed::class)) … else return;`. scssphp comes from `be_style`, so the class silently does not exist when be_style is missing.
- Public API called from templates (`getCSSFile`, `getCSSMinFile`, `getCombined*File`, `getJSFile`, `getJSCodeFromTemplate`, `getResourceFile`, …) returns a URL string. `.scss` inputs are compiled to `<name>.min.css` in the CSS output dir. All output is regenerated lazily by comparing `filemtime` of sources vs. output; combined files also store an `md5` of the source-file list in their first line (`res_id`) so changing the argument list forces a rebuild. SCSS recompiles when any file in the source file's directory tree is newer than the output.
- URLs get a version segment: `style.css` becomes `style.<mtime>.css`. This needs the rewrite rule shown on the addon's "Anleitung" page (`^(.*)\.[0-9]+\.(css|js)$ -> $1.$2`). With `absolute_paths` set, the URL is prefixed with `rex_yrewrite::getFullPath()` if yrewrite exists, else `rex::getServer()`.
- Minification uses `matthiasmullie/minify` (`vendor/minify`, loaded via manual `require_once`, no autoloader) for CSS/JS and `vendor/Minify_HTML.php` for HTML. Inline JS minification inside HTML is deliberately disabled (no `jsMinifier` option) because it broke output. Use a fresh `Minify\CSS` per `<style>` block, `Minify::add()` accumulates.
- SCSS compile errors are echoed straight into the page (closing the open `<link>` tag first) and `exit`.

Backend: `pages/index.php` dispatches to `pages/einstellungen.php` (settings form writing `config[...]` via `setConfig`) and `pages/anleitung.php` (usage guide). `install.php` seeds default config only when none exists. Config keys: `css_dir`, `scss_dir`, `scss_css_output_dir`, `js_dir`, `js_output_dir`, `minify_html`, `minify_css`, `minify_js`, `minify_single_line`, `absolute_paths`.

## Conventions

- Version number lives in three places and must be bumped together: `package.yml`, the `@version` docblock in `lib/class.massif_minify.php`, and the "Last Changes" section in `README.md`.
- Translations go in both `lang/de_de.lang` and `lang/en_gb.lang`, prefixed `massif_minify_`.
- Vendor updates are done by replacing the `vendor/minify` and `vendor/path-converter` trees wholesale (see commit history).
