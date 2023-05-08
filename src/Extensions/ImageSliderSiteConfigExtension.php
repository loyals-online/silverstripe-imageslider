<?php

namespace Loyals\ImageSlider\Extensions;

use Loyals\ImageSlider\Service\SiteHelper;
use Page;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\CheckboxSetField;

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
        $pageClasses = ClassInfo::subclassesFor(Page::class);

        // make it shortform (as currently stored pre-v4)
        foreach ($pageClasses as &$pageClass) {
            $pageClass = SiteHelper::ClassShortName($pageClass);
        }
        $pageClasses = array_combine($pageClasses, $pageClasses);

        // Get all excluded pages from config. Extra pages can be excluded in the site config using the YML settings
        $pageExcludes = Config::inst()->get('ImageSliderConfig', 'PageTypeExcludes');
        foreach ($pageExcludes as $page) {
            unset($pageClasses[SiteHelper::ClassShortName($page)]);
        }

        $fields->addFieldsToTab("Root.Modules", [
            HeaderField::create('ImageSlider', 'Image Slider'),
            CheckboxSetField::create(
                'ImageSliderPageTypes',
                _t('ImageSlider.SiteConfigSelectPageTypes', 'Select page types'),
                $pageClasses
            )
                // need to use setDefaultItems, otherwise value is empty (extension issue?)
                ->setDefaultItems($this->getImageSliderPageTypesArray()),
            HeaderField::create('SingleImage', 'Single Image'),
            CheckboxSetField::create(
                'SingleImagePageTypes',
                _t('ImageSlider.SiteConfigSelectPageTypes','Select page types'),
                $pageClasses
            )
                // need to use setDefaultItems, otherwise value is empty (extension issue + v3/v4?)
                ->setDefaultItems($this->getSingleImagePageTypesArray()),
        ]);

        return $fields;
    }

    public function getImageSliderPageTypesArray(){
        $values = $this->owner->ImageSliderPageTypes;

        if (is_string($values) && ($arr = explode(",", trim($values, "[]")))) {
            // SS can store it as 'a,b,c' of as '["a","b","c"]'
            array_walk($arr, function (&$v, $k) {
                $v = trim($v, "\",");
            });
            $values = array_combine($arr, $arr);
        }

        return $values ?? [];
    }

    public function getSingleImagePageTypesArray(){
        $values = $this->owner->SingleImagePageTypes;

        if (is_string($values) && ($arr = explode(",", trim($values, "[]")))) {
            // SS can store it as 'a,b,c' of as '["a","b","c"]'
            array_walk($arr, function (&$v, $k) {
                $v = trim($v, "\",");
            });
            $values = array_combine($arr, $arr);
        }

        return $values ?? [];
    }
}
