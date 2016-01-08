<?php namespace Willing\Scss\Classes;

require_once dirname(__dir__).'/vendor/leafo/scssphp/scss.inc.php';

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;
use Leafo\ScssPhp;

/**
 *
 */
class Compiler implements FilterInterface
{

    public function filterLoad(AssetInterface $asset)
    {
        $scss = new \scssc();

        if ($dir = $asset->getSourceDirectory()) {
            $scss->setImportPaths($dir);
        }

        $asset->setContent($scss->compile($asset->getContent()));
    }

    public function filterDump(AssetInterface $asset)
    {
    }

}
