<?php

/**
 * Created by PhpStorm.
 * User: jpvanderpoel
 * Date: 14/09/16
 * Time: 14:51
 */
class ImageSliderPageExtension extends DataExtension
{
    private static $many_many = [
        'ImageSlides' => 'ImageSlide',
    ];

    private static $many_many_extraFields = [
        'ImageSlides' => ['SortOrder' => 'Int'],
    ];

    function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);

        $labels['ImageSlides'] = _t('ImageSlider.ImageSlides', 'Image Slides');

        return $labels;
    }

    public function updateCMSFields(FieldList $fields)
    {
        $editableFields = [
            'Name'    => [
                'title' => _t('ImageSlider.Name', 'Name'),
                'field' => 'ReadonlyField',
            ],
            'Title'   => [
                'title' => _t('ImageSlider.Title', 'Title'),
                'field' => 'ReadonlyField',
            ],
            'Enabled' => [
                'title'    => _t('ImageSlider.Enabled', 'Enabled'),
                'callback' => function ($record, $column, $grid) {
                    return CheckboxField::create($column, '');
                },
            ],
        ];

        $config = GridFieldConfig_RecordEditor::create()
            ->removeComponentsByType('GridFieldDeleteAction')
            ->removeComponentsByType('GridFieldDataColumns')
            ->addComponent(new GridFieldOrderableRows('SortOrder'))
            ->addComponent(new GridFieldDataColumns(), 'GridFieldButtonRow')
            ->addComponent((new GridFieldEditableColumns())->setDisplayFields($editableFields), 'GridFieldButtonRow')
            ->addComponent(new GridFieldDeleteAction(false));

        $gridfield = GridField::create('ImageSlides', _t('ImageSlider.ImageSlides', 'Image Slides'), $this->owner->ImageSlides()
            ->sort('SortOrder ASC'), $config);

        $fields->addFieldsToTab("Root.Image Slider", [
            $gridfield,
        ]);

        return $fields;
    }

    public function MultipleSlides()
    {
        if (count($this->owner->ImageSlides()
                ->filter(['Enabled' => 1])) > 1
        ) {
            return $this->owner->ImageSlides()
                ->filter(['Enabled' => 1])
                ->sort('SortOrder');
        }
    }

    public function SingleImage()
    {
        if (count($this->owner->ImageSlides()
                ->filter(['Enabled' => 1])) == 1
        ) {
            return $this->owner->ImageSlides()
                ->filter(['Enabled' => 1])
                ->sort('SortOrder')
                ->first();
        }
    }

}