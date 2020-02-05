<?php
/**
 * MASSIF Minify - komprimiert. minimiert. massiv.
 * basiert in grossen Teilen auf dem resource_includer von RexDude
 * 
 * WICHTIG: setzt scss_php von Leafo voraus (im Addon be_style enthalten)
 *
 * @link https://github.com/ynamite/massif_minify
 *
 * @author studio[at]massif.ch Yves Torres
 *
 * @package redaxo5
 * @version 1.3.0
 */

use MatthiasMullie\Minify;		
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Formatter\Compressed;

if(class_exists(Compressed::class)) {
	class scss_formatter extends ScssPhp\ScssPhp\Formatter\Compressed
	{
	}
} else 
	return;
	
class massif_minify {
	
	protected static $cssDir;
	protected static $cssPath;
	
	protected static $scssDir;
	protected static $scssPath;
	
	protected static $jsDir;
	protected static $jsPath;

	protected static $cssOutDir;
	protected static $cssOutPath;

	protected static $jsOutDir;
	protected static $jsOutPath;
	
	protected static $minify_js;
	protected static $minify_css;
	protected static $minify_to_single_line;

	protected static $absolute_paths;

	public static function init() {
		
		$search_it_indexer = rex_get('search_it_build_index','string');
		$search_it_highlighter = rex_get('search_highlighter','string');
		if($search_it_indexer == "" && $search_it_highlighter != "") {
			$search_it_indexer = 'search_it_highlighter';
		}
		
		$addon = rex_addon::get('massif_minify');

	    //throw new rex_exception('test');

		$cssDir = $addon->getConfig('css_dir');
		$scssDir = $addon->getConfig('scss_dir');
		$cssOutDir = $addon->getConfig('scss_css_output_dir');
		if(!$cssOutDir) {
			$cssOutDir = $cssDir;
		}
		
		$jsDir = $addon->getConfig('js_dir');
		$jsOutDir = $addon->getConfig('js_output_dir');
		if(!$jsOutDir) {
			$jsOutDir = $jsDir;
		}
		
		self::$minify_to_single_line = $addon->getConfig('minify_single_line');

		self::$cssDir = self::prepareDir($cssDir);
		self::$scssDir = self::prepareDir($scssDir);
		self::$cssOutDir = self::prepareDir($cssOutDir);
		self::$jsDir = self::prepareDir($jsDir);
		self::$jsOutDir = self::prepareDir($jsOutDir);

		self::$cssPath = self::preparePath($cssDir);
		self::$scssPath = self::preparePath($scssDir);
		self::$cssOutPath = self::preparePath($cssOutDir);
		self::$jsPath = self::preparePath($jsDir);
		self::$jsOutPath = self::preparePath($jsOutDir);
		
		self::$minify_css = $addon->getConfig('minify_css');
		self::$minify_js = $addon->getConfig('minify_js');

		self::$absolute_paths = $addon->getConfig('absolute_paths');

	    if ($addon->getConfig('minify_html') && !rex::isBackend() && $search_it_indexer == "") {
			
			rex_extension::register('OUTPUT_FILTER', 'massif_minify::minifyHTML', rex_extension::LATE);
	    }
	}
	
	public static function getCSSFile($file, $vars = array()) {
		if (self::isHttpAddress($file)) {
			return $file;
		} else {
			$fileExtension = self::getFileExtension($file);

			if ($fileExtension == 'scss') {
				$file = self::getCompiledCSSFile($file, $fileExtension, $vars);
			} elseif(self::$minify_css) {
				return self::getCombinedCSSMinFile($file, array($file));
			}

			if(self::$absolute_paths)
				return rtrim(rex::getServer(), "/") . self::$cssOutDir . self::getFileWithVersionParam($file, self::$cssOutPath);
			else
				return self::$cssOutDir . self::getFileWithVersionParam($file, self::$cssOutPath);
		}
	}
	
	public static function getCSSMinFile($file, $vars = array()) {
		self::$minify_css = true;
		$minFile = self::replaceFileExtension($file, 'min.css');
		return self::getCSSFile($minFile, $vars);
	}
	
	public static function getJSFile($file) {
		if(self::$minify_js) {
			//$combinedFile = self::replaceFileExtension($file, 'min.js');
			return self::getCombinedJSFile($file, array($file));
		}
		return self::_getJSFile($file);
	}

	public static function getJSMinFile($file) {
		self::$minify_js = true;
		$minFile = self::replaceFileExtension($file, 'min.js');
		return self::getJSFile($minFile);
	}

	public static function getResourceFile($fileWithPath) {
		$info = pathinfo($fileWithPath);
		$dir = $info['dirname'] . '/';

		if ($info['extension'] == 'css' || $info['extension'] == 'js') {
			return self::prepareDir($info['dirname']) . self::getFileWithVersionParam($info['basename'], $info['dirname']);
		} else {
			return self::prepareDir($info['dirname']) . $info['basename'];
		}
	}

	public static function getCombinedCSSFile($combinedFile, $sourceFiles) {
		self::combineFiles($combinedFile, self::$cssOutPath, self::$cssPath, $sourceFiles);

		return self::getCSSFile($combinedFile);
	}

	public static function getCombinedCSSMinFile($combinedFile, $sourceFiles) {
		self::$minify_css = true;
		$combinedFile = self::replaceFileExtension($combinedFile, 'min.css');
		self::combineFiles($combinedFile, self::$cssOutPath, self::$cssPath, $sourceFiles);
		self::$minify_css = false;
	    return self::getCSSFile($combinedFile);
	}

	public static function getCSSCodeFromTemplate($templateId, $simpleMinify = true) {
		$template = new rex_template($templateId);

		return self::getCSSCode($template->getFile(), $simpleMinify);
	}

	protected static function getCSSCode($includeFileWithPath, $simpleMinify = true) {
		$interpretedPhp = '';

		// interpret css as php
		ob_start();

		@include($includeFileWithPath);
		$interpretedPhp = ob_get_contents();

		ob_end_clean();

		if ($simpleMinify) {
			$interpretedPhp = self::getMinifiedContent($interpretedPhp, 'css');
		} 

		return $interpretedPhp;
	}
	
	public static function getCombinedJSFile($combinedFile, $sourceFiles) {
		if(self::$minify_js) {
			$combinedFile = self::replaceFileExtension($combinedFile, 'min.js');
		}
		self::combineFiles($combinedFile, self::$jsOutPath, self::$jsPath, $sourceFiles);

		return self::_getJSFile($combinedFile);
	}
	
	public static function getCombinedJSMinFile($combinedFile, $sourceFiles) {
		self::$minify_js = true;
		$combinedFile = self::replaceFileExtension($combinedFile, 'min.js');
		self::combineFiles($combinedFile, self::$jsOutPath, self::$jsPath, $sourceFiles);
		return self::_getJSFile($combinedFile);
	}

	public static function getGeneratedCSSFile($file, $vars = array()) {
		
		$addon = rex_addon::get('massif_minify');
		if ($addon->getConfig('minify_css'))
			return self::getGeneratedCSSMinFile($file, $vars);
		
		return self::getCSSFile($file, $vars);
	}
	
	public static function getGeneratedCSSMinFile($file, $vars = array()) {
		
		$fileExtension = self::getFileExtension($file);
		
		if (self::isHttpAddress($file) || $fileExtension === 'css')
			  return $file;
				
		$file = self::getCompiledCSSFile($file, $fileExtension, $vars);

		return self::$cssDir . self::getFileWithVersionParam($file, self::$cssPath);
	}

	public static function getJSCodeFromTemplate($templateId, $simpleMinify = true) {
		$template = new rex_template($templateId);

		return self::getJSCode($template->getFile(), $simpleMinify);
	}

	public static function getJSCodeFromFile($file, $simpleMinify = true) {
		return self::getJSCode(self::$jsPath . $file, $simpleMinify);
	}

	public static function minifyHTML(\rex_extension_point $ep) {

		$addon = rex_addon::get('massif_minify');
				
	    require_once rex_path::addon('massif_minify', 'vendor/minify/src/Minify.php');
	    require_once rex_path::addon('massif_minify', 'vendor/minify/src/CSS.php');
	    require_once rex_path::addon('massif_minify', 'vendor/minify/src/JS.php');
	    require_once rex_path::addon('massif_minify', 'vendor/minify/src/Exception.php');
			
		$cssMinifier = new Minify\CSS();
		$jsMinifier = new Minify\JS();
		
		$html = Minify_HTML::minify($ep->getSubject(), array(
			'cssMinifier' => function($css) use ($cssMinifier) {
				$cssMinifier->add($css);
				return $cssMinifier->minify();
			},/*
			'jsMinifier' => 'JSMinPlus::minify',
			,
			'jsMinifier' => function($js) use ($jsMinifier){
				$jsMinifier->add($js);
				return $jsMinifier->minify();
			},*/
			'xhtml' => false
		));
		if(self::$minify_to_single_line) {
			$html = preg_replace(['/<!--(.*)-->/Uis',"/[[:blank:]]+/"], ['',' '], str_replace(["\n","\r","\t"], ' ', $html));
		} 

		$ep->setSubject($html);
		unset($html);
		
	}
	
	protected static function _getJSFile($file) {
		if (self::isHttpAddress($file)) {
			return $file;
		} else {
			if(self::$absolute_paths)
				return rtrim(rex::getServer(), "/") . self::$jsOutDir . self::getFileWithVersionParam($file, self::$jsOutPath);	
			else
				return self::$jsOutDir . self::getFileWithVersionParam($file, self::$jsOutPath);	
		}
	}

	protected static function getJSCode($includeFileWithPath, $simpleMinify = true) {
		$interpretedPhp = '';

		// interpret js as php
		ob_start();

		@include($includeFileWithPath);
		$interpretedPhp = ob_get_contents();

		ob_end_clean();

		if ($simpleMinify) {
			$interpretedPhp = self::simpleJSMinify($interpretedPhp);
		} 

		return $interpretedPhp;
	}

	protected static function getCompiledCSSFile($sourceFile, $sourceFileType, $vars = array()) {
	
	    $cssFile = self::replaceFileExtension($sourceFile, 'min.css');

	    $sourceFileWithPath = self::$scssPath . $sourceFile;
	    $cssFileWithPath = self::$cssOutPath . $cssFile;

	    $cssFileMTime = @filemtime($cssFileWithPath);
		$sourceFileMTime = 0;
	
	    $path = pathinfo($sourceFileWithPath);
		
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path['dirname']), RecursiveIteratorIterator::CHILD_FIRST);
		foreach ($iterator as $fileinfo) {
		    if ($fileinfo->isFile()) {
		        if ($fileinfo->getMTime() > $sourceFileMTime) {
		            $sourceFileMTime = $fileinfo->getMTime();
		        }
		    }
		}            
	    if ($cssFileMTime == false || $sourceFileMTime > $cssFileMTime) {
	          // compile scss
	          self::compileCSS($sourceFileWithPath, $cssFileWithPath, $sourceFileType, $vars);
	    }
	
	    // return css file
	    return $cssFile;
	}

	protected static function compileCSS($sourceFileWithPath, $cssFileWithPath, $sourceFileType, $vars) {

        // go on even if user "stops" the script by closing the browser, closing the terminal etc.
        ignore_user_abort(true);
        // set script running time to unlimited
        set_time_limit(0);	
	
	    if (!file_exists($sourceFileWithPath)) {
	          return;
	    }
	
	    // get content of source file
	    $sourceFileContent = file_get_contents($sourceFileWithPath);
	
	    // strip comments out
	    $sourceFileContent = self::stripCSSComments($sourceFileContent);
	
	    // get file path
	    $path = pathinfo($sourceFileWithPath);
	
	    // compile source file to css
	    try {
			
			//if ($sourceFileContent == $compiledCSS) {
			// include compiler
			$formatter = new scss_formatter;
			$formatter->indentChar = "\t";
			$formatter->close = "}" . PHP_EOL;
			$formatter->assignSeparator = ": ";
			
			/*
			$scss = new scssc();
			$scss->setFormatter($formatter);
			*/
			$compiler = new Compiler();
			$compiler->setFormatter($formatter);
			$compiler->addImportPath($path['dirname']);
			
			$compiledCSS = $compiler->compile($sourceFileContent);
			//}
			
	    } catch (Exception $e) {
	          echo '" />'; // close tag as we are probably in an open link tag in head section of website 
	          echo '<p style="margin: 5px;"><code>';
	          echo '<strong>' . strtoupper($sourceFileType) . ' Compile Error:</strong><br/>';
	          echo $e->getMessage();
	          echo '</code></p>';
	          exit;
	    }
	
	
	    /*
	     * min
	     */
		 if(self::$minify_css)
		 	$compiledCSS = self::getMinifiedContent($compiledCSS, 'css');
	
	    // write css
	    rex_file::put($cssFileWithPath, $compiledCSS);
	}

	protected static function getMinifiedContent($content, $fileExtension) {
		
	    require_once rex_path::addon('massif_minify', 'vendor/minify/src/Minify.php');
	    require_once rex_path::addon('massif_minify', 'vendor/minify/src/CSS.php');
	    require_once rex_path::addon('massif_minify', 'vendor/minify/src/JS.php');
	    require_once rex_path::addon('massif_minify', 'vendor/minify/src/Exception.php');
			
		switch($fileExtension) {
			case 'css':
				$minifier = new Minify\CSS();
			break;
			case 'js':
				$minifier = new Minify\JS();
			break;
		}
		$minifier->add($content);
		$content = $minifier->minify();
		
		return $content;
	}
	
	protected static function prepareDir($dir) {
		return rex_url::frontend($dir, 'rex_path::RELATIVE');
	}

	protected static function preparePath($dir) {
		rex_dir::create($dir, false);
		return rex_path::frontend($dir);
	}

	protected static function replaceFileExtension($file, $newExtension) {
		$info = pathinfo($file);

		return $info['filename'] . '.' . $newExtension;
	}

	protected static function getFileExtension($file) {
		$info = pathinfo($file);

		if (isset($info['extension'])) {
			return $info['extension'];
		} else {
			return '';
		}
	}

	protected static function getFileWithVersionParam($file, $path) {

		$mtime = @filemtime($path . '/' . $file); 

		if ($mtime != false) {
			return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
		} else {
			return $file;
		}
	}

	protected static function combineFiles($combinedFile, $outPath, $filePath, $sourceFiles = array()) {
		$combinedFileContent = '';
		$combinedFileWithPath = $outPath . $combinedFile;
		$combinedFileMTime = @filemtime($combinedFileWithPath);
		$doCombine = false;
		$hashString = '';

		// get hash string first
		foreach ($sourceFiles as $file) {
			$hashString .= $file;
		}

		// check if combined file needs to be created
		if ($combinedFileMTime == false) {
			// combined file does not exist
			$doCombine = true;
		} else {
			// check filemtime of source files
			foreach ($sourceFiles as $file) {
				// compile first if scss/less
				$fileExtension = self::getFileExtension($file);
				if ($fileExtension == 'scss') {
					$fileWithPath = self::$scssPath . $file;
				} else {
					$fileWithPath = $filePath . $file;
				}
				$fileMTime = filemtime($fileWithPath);

				if ($combinedFileMTime == false || $fileMTime > $combinedFileMTime) {
					// filemtime of one of the source files is newer then of combined file
					$doCombine = true;
					break;
				}
			}

			// check resource id inside combined file (when user changed function arguments for example)
			$fileHandle = @fopen($combinedFileWithPath, 'r');
			$firstLine = '';

			if ($fileHandle != false) {
				$firstLine = fgets($fileHandle);
				fclose($fileHandle);
			}

			$hashStringFromCombinedFile = str_replace('res_id', '', $firstLine);
			$hashStringFromCombinedFile = trim($hashStringFromCombinedFile, " \t\r\n:*/");

			if (self::isValidMd5($hashStringFromCombinedFile) && $hashStringFromCombinedFile != md5($hashString)) {
				$doCombine = true;
			}
		}
		
		// combine files if necessary
		if ($doCombine) {
			foreach ($sourceFiles as $file) {
				$fileWithPath = $filePath . $file;

				// compile first if scss/less
				$fileExtension = self::getFileExtension($file);

				if ($fileExtension == 'scss') {
					$compiledCSS = self::getCompiledCSSFile($file, $fileExtension);
					$fileWithPath = $filePath . $compiledCSS;
				}
				
				// now combine
				if (file_exists($fileWithPath)) {
					$combinedFileContent .= file_get_contents($fileWithPath);
					if ($fileExtension == 'scss') {
						unlink($fileWithPath);
					}
				} elseif (!self::$minify_css) {
					$combinedFileContent .= '/* file not found: ' . $fileWithPath . ' */';
				}
				
				if (!self::$minify_css)
					$combinedFileContent .=  PHP_EOL . PHP_EOL;
					
				//
			}

			/*
			* min
			*/
			if (($fileExtension == 'js' && self::$minify_js) || ($fileExtension == 'css' && self::$minify_css))
				$combinedFileContent = self::getMinifiedContent($combinedFileContent, $fileExtension);
				
			// add hash
			$combinedFileContent = '/* res_id: ' . md5($hashString) . ' */' . PHP_EOL . PHP_EOL . $combinedFileContent;
			if(self::$minify_to_single_line && $fileExtension != 'js') {
				$combinedFileContent = preg_replace(['/<!--(.*)-->/Uis',"/[[:blank:]]+/"], ['',' '], str_replace(["\n","\r","\t"], ' ', $combinedFileContent));
			} 
			// write combined file
			rex_file::put($combinedFileWithPath, $combinedFileContent);
			
		}
	}

	protected static function simpleJSMinify($code) {
		
		return self::getMinifiedContent($code, 'js');
	}

	protected static function isHttpAddress($file) {
		if ((strpos($file, 'http') === 0) || strpos($file, '//') === 0) {
			return true;
		} else {
			return false;
		}
	}

	protected static function isValidMd5($md5 = '') {
		return preg_match('/^[a-f0-9]{32}$/', $md5);
	}

	protected static function stripCSSComments($css) {
		return preg_replace('/\s*(?!<\")\/\*[^\*]+\*\/(?!\")\s*/', '', $css);
	}
}


