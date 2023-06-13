<?php

namespace Loyals\ImageSlider\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\Core\Manifest\ModuleResourceLoader;

class ImageSliderControllerInit extends Extension
{
    /**
     * Retrieve the required javascripts
     *
     * @return array
     */
    public function getRequiredJavascript()
    {

        return [
            ModuleResourceLoader::singleton()->resourcePath('mediaweb/silverstripe-imageslider:javascript/slick/slick.min.js'),
            ModuleResourceLoader::singleton()->resourcePath('mediaweb/silverstripe-imageslider:javascript/youtubebackground/jquery.youtubebackground.min.js'),
            ModuleResourceLoader::singleton()->resourcePath('mediaweb/silverstripe-imageslider:javascript/imageslider.js'),
        ];
    }
}
