<?php namespace Willing\Scss\Classes;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\ScssphpFilter;
use Assetic\Filter\HashableInterface;
use Assetic\Factory\AssetFactory;
use Cms\Classes\Theme;

/**
 * a custom scss Filter with hash function for children
 */
class ScssFilter extends ScssphpFilter implements HashableInterface
{
    //the files to check children in the hash function
    protected $files = [];

    protected $variables = [];

    public function __construct(&$files)
    {
        //set the files wich are changed by an event
        $this->files = &$files;
    }


    public function setPresets(array $presets)
    {
        $this->variables = array_merge($this->variables, $presets);
    }

    public function setVariables(array $variables)
    {
        $this->variables = array_merge($this->variables, $variables);
    }

    public function addVariable($variable)
    {
        $this->variables[] = $variable;
    }

    public function filterLoad(AssetInterface $asset){
        parent::setVariables($this->variables);
        parent::filterLoad($asset);
    }

    public function hash()
    {
        $themePath = themes_path(Theme::getActiveTheme()->getDirName());
        $factory = new AssetFactory($themePath);

        $allFiles = [];

        foreach ($this->files as $file) {
            $children = $this->getChildren($factory, file_get_contents($themePath.'/'.$file), $themePath.'/'.dirname($file));
            foreach ($children as $child) {
                $allFiles = array_merge($allFiles, $child->all());
            }
        }

        $modifieds = [];

        foreach ($allFiles as $file) {
            $modifieds[] = $file->getLastModified();
        }

        return md5(implode('|', $modifieds));
    }
}
