<?php namespace Willing\Scss\Classes;

require_once dirname(__dir__).'/vendor/leafo/scssphp/scss.inc.php';

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;
use Leafo\ScssPhp;

/**
 * a custom compiler for Scss
 */
class Compiler implements FilterInterface
{
    /**
     * function thats called to compile a file
     * @param  AssetInterface $asset the asset object with the file
     * @return void
     */
    public function filterLoad(AssetInterface $asset)
    {
        $scss = new \scssc();

        if ($dir = $asset->getSourceDirectory()) {
            $scss->setImportPaths($dir);
        }

        $asset->setContent($scss->compile($asset->getContent()));
    }
    /**
     * Filters an asset just before it's dumped.
     *
     * @param AssetInterface $asset An asset
     */
    public function filterDump(AssetInterface $asset)
    {}

}
