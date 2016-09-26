<?php

/**
 * Created by PhpStorm.
 * User: jpvanderpoel
 * Date: 14/09/16
 * Time: 14:51
 */
class ImageSliderPageExtension extends DataExtension
{
    private static $db = [
        'ImageSliderEnabled'   => 'Boolean',
    ];

    private static $many_many = [
        'ImageSlides' => 'ImageSlide',
    ];

    private static $many_many_extraFields = [
        'ImageSlides' => ['SortOrder' => 'Int'],
    ];

    function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['ImageSliderEnabled'] = _t('ImageSlider.EnableImageSlider', 'Enable Image Slider');
        $labels['ImageSlides'] = _t('ImageSlider.ImageSlides', 'Image Slides');

        return $labels;
    }

    public function updateCMSFields(FieldList $fields)
    {

        $config = GridFieldConfig_RecordEditor::create()
            ->removeComponentsByType('GridFieldDeleteAction')
            ->addComponent(new GridFieldDeleteAction(false))
            ->addComponent(new GridFieldOrderableRows('SortOrder'));

        $fields->addFieldsToTab("Root.Image Slider", [
            FieldGroup::create(CheckboxField::create('ImageSliderEnabled', ''))->setTitle(_t('ImageSlider.EnableImageSlider', 'Enable Image Slider')),
            GridField::create('ImageSlides', _t('ImageSlider.ImageSlides', 'Image Slides'), $this->owner->ImageSlides()
                ->sort('SortOrder ASC'), $config),
        ]);

        return $fields;
    }

}