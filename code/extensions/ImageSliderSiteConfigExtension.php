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

        $fields->addFieldsToTab("Root.ImageSlider", [
            CheckboxSetField::create('ImageSliderPageTypes', 'Select page types for image slider functionality', $pageClasses),
            CheckboxSetField::create('SingleImagePageTypes', 'Select page types for single image functionality', $pageClasses)
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