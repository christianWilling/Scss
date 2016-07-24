<?php namespace Willing\Scss;

require_once __dir__.'/vendor/autoload.php';
require_once __dir__.'/classes/ScssFilter.php';

use System\Classes\PluginBase;
use Cms\Classes\Theme;
use Event;

class Plugin extends PluginBase
{
    /**
     * @var boolean Determine if this plugin should have elevated privileges.
     */

    public $elevated = true;

    /**
     * [pluginDetails description]
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Scss',
            'description' => 'adds scss support',
            'author'      => 'Christian Willing',
            'icon'        => 'icon-pencil'
        ];
    }

    /**
     * set the filter to compile scss files
     */
    public function boot()
    {
        //the files wich children are included in the hash function of the filter
        $files = [];

        \System\Classes\CombineAssets::registerCallback(function($combiner) use (&$files){
            if ($combiner->useMinify) {
                $combiner->registerFilter('scss', new \October\Rain\Support\Filters\StylesheetMinify);
            }
            //init the filter class with the reverrence to the hash files
            $filter = new \Willing\Scss\Classes\ScssFilter($files);

            //set the output formate
            $filter->setFormatter('Leafo\ScssPhp\Formatter\Expanded');

            //set some helpfull variables
            $filter->setVariables([
                'theme-folder' => '"'.url('/themes/'.Theme::getActiveTheme()->getDirName()).'/"',
            ]);

            //set the filter
            $combiner->registerFilter('scss', $filter);
            $combiner->registerFilter('scss', new \Assetic\Filter\CssRewriteFilter);
        });

        //get current files for the hash function and give them to it by reverrence
        Event::listen('cms.combiner.beforePrepare', function($combiner, $assets) use (&$files) {
            $files = [];
            foreach ($assets as $asset) {
                if(pathinfo($asset)['extension'] == 'scss'){
                    $files[] = $asset;
                }
            }
        });
    }
}
