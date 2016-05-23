<?php namespace Willing\Scss\Classes;

require_once dirname(__dir__).'/vendor/autoload.php';

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;
use Assetic\Filter\HashableInterface;
use Leafo\ScssPhp\Compiler as ScssCompiler;
use Cms\Classes\Theme;
use Exception;
use File;

/**
 * a custom compiler for Scss
 */
class Compiler implements FilterInterface, HashableInterface
{
    /**
     * function thats called to compile a file
     * @param  AssetInterface $asset the asset object with the file
     * @return void
     */
    public function filterLoad(AssetInterface $asset)
    {
        try {
            $scss = new ScssCompiler();

            $scss->setFormatter('Leafo\ScssPhp\Formatter\Expanded');

            $scss->setVariables([
                'theme-folder' => '"'.url('/themes/'.Theme::getActiveTheme()->getDirName()).'/"',
            ]);

            $dir = $asset->getSourceDirectory();

            $scss->addImportPath(function($path) use ($dir, &$paths) {

                $path = File::normalizePath($dir.'/'.$path);

                $dotScss = $path.'.scss';

                $pathParts = explode('/', $path);
                $filenameKey = last(array_keys($pathParts));
                $pathParts[$filenameKey] = '_'.$pathParts[$filenameKey];
                $underscoreDotScss = implode('/', $pathParts).'.scss';

                if(File::exists($path)){
                    $pathOut = $path;
                } elseif(File::exists($dotScss)) {
                    $pathOut = $dotScss;
                } elseif((File::exists($underscoreDotScss))) {
                    $pathOut = $underscoreDotScss;
                } else {
                    throw new Exception('File not found: '.$path);
                }

                return $pathOut;
            });

            $code = $scss->compile($asset->getContent());
            $asset->setContent($code);
        } catch (Exception $e) {
            $msg = $e->getMessage();
            $msg = str_replace('\\', '\\\\', $msg);
            $msg = str_replace("\n", '\\A', $msg);
            $con = 'body:before {white-space: pre;font-family: monospace;content: "'.$msg.'"';
            $asset->setContent($con);
        }
    }
    /**
     * Filters an asset just before it's dumped.
     *
     * @param AssetInterface $asset An asset
     */
    public function filterDump(AssetInterface $asset)
    {}

    public function hash()
    {
        dd('asd');
        return '';
    }

}
