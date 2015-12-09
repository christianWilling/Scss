<?php namespace Willing\Scss;

use System\Classes\PluginBase;
use Event;

class Plugin extends PluginBase
{
    function __construct() {
        \System\Classes\CombineAssets::registerCallback(function($combiner){
            if ($combiner->useMinify) {
                $this->registerFilter('scss', new \October\Rain\Support\Filters\StylesheetMinify);
            }
            $combiner->registerFilter('scss', new \Willing\Scss\Classes\Compiler);
        });
   }
    public function pluginDetails()
    {
        return [
            'name'        => 'Scss',
            'description' => 'adds scss support',
            'author'      => 'Christian Willing',
            'icon'        => 'icon-pencil'
        ];
    }

    public function boot()
    {

    }
}
