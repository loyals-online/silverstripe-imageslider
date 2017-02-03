<?php

/**
 * Extension for SiteConfig that adds image slider configuration
 *
 * @Author Jan-Pieter van der Poel
 * @Alias  jpvdpoel
 * @Email  jp@loyals.nl
 */
class ImageSliderSiteConfigExtension extends DataExtension
{
    private static $db = [
        'ImageSliderPageTypes' => 'Text',
        'SingleImagePageTypes' => 'Text'

    ];

    public function updateCMSFields(FieldList $fields)
    {
        // Get all subclasses of Page
        $pageClasses = ClassInfo::subclassesFor('Page');

        // Get all excluded pages from config. Extra pages can be excluded in the site config using the YML settings
        $pageExcludes = Config::inst()->get('ImageSliderConfig', 'PageTypeExcludes');

        foreach ($pageExcludes as $page) {
            unset($pageClasses[$page]);
        }

        $fields->addFieldsToTab("Root.Modules", [
            HeaderField::create('Image Slider'),
            CheckboxSetField::create('ImageSliderPageTypes', _t('ImageSlider.SiteConfig.SelectPageTypes', 'Select page types'), $pageClasses),
            HeaderField::create('Single Image'),
            CheckboxSetField::create('SingleImagePageTypes', _t('ImageSlider.SiteConfig.SelectPageTypes', 'Select page types'), $pageClasses)
        ]);

        return $fields;
    }

    public function getImageSliderPageTypesArray(){
        return explode(",", $this->owner->ImageSliderPageTypes);
    }

    public function getSingleImagePageTypesArray(){
        return explode(",", $this->owner->SingleImagePageTypes);
    }
}