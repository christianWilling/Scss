<?php namespace Willing\Scss;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    /**
     * set the filter to compile scss files
     */
    function __construct() {
        \System\Classes\CombineAssets::registerCallback(function($combiner){
            if ($combiner->useMinify) {
                $combiner->registerFilter('scss', new \October\Rain\Support\Filters\StylesheetMinify);
            }
            $combiner->registerFilter('scss', new \Willing\Scss\Classes\Compiler);
        });
    }

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
}
