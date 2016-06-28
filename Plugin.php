<?php namespace Willing\Scss;

use System\Classes\PluginBase;

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
        \System\Classes\CombineAssets::registerCallback(function($combiner){
            if ($combiner->useMinify) {
                $combiner->registerFilter('scss', new \October\Rain\Support\Filters\StylesheetMinify);
            }
            //init the filter class
            $filter = new \Assetic\Filter\ScssphpFilter;

            //set the output formate
            $filter->setFormatter('Leafo\ScssPhp\Formatter\Expanded');

            //set some helpfull variables
            $filter->setVariables([
                'theme-folder' => '"'.url('/themes/'.Theme::getActiveTheme()->getDirName()).'/"',
            ]);


            $combiner->registerFilter('scss', $filter);
        });
    }
}
