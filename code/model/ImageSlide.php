<?php

/**
 * Created by PhpStorm.
 * User: jpvanderpoel
 * Date: 14/09/16
 * Time: 14:20
 */
class ImageSlide extends DataObject
{

    static $singular_name = 'Image Slide';

    static $plural_name = 'Image Slides';

    static $db = [
        'Name'          => 'Varchar(255)',
        'Title'         => 'Varchar(255)',
        'SubTitle'      => 'Varchar(255)',
        'ButtonText'    => 'Varchar(255)',
        'LinkType'      => 'Enum("None,Internal,External,Email,Telephone","None")',
        'LinkExternal'  => 'Varchar(255)',
        'LinkEmail'     => 'Varchar(255)',
        'LinkTelephone' => 'Varchar(255)',
        'Enabled'       => 'Boolean',

    ];

    private static $has_one = [
        'Image' => 'Image',
        'Page'  => 'Page',
    ];

    public static $summary_fields = [
        'Thumbnail' => 'Image'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
            'LinkExternal',
            'Image',
            'PageID',
            'LinkEmail',
            'LinkTelephone',
            'Enabled'
        ]);

        $fields->insertBefore(
            UploadField::create('Image', _t('ImageSlider.Image', 'Image'))
                ->setFolderName('image-slider-images')
                ->setDisplayFolderName('image-slider-images'),
            'ButtonText'
        );

        $fields->insertBefore(
            CheckboxField::create('Enabled', _t('ImageSlider.Enabled', 'Enabled')),
            'Name'
        );

        $fields->insertAfter(
            CompositeField::create(
                DisplayLogicWrapper::create(
                    TreeDropdownField::create(
                        'PageID',
                        _t('ImageSlider.LinkInternal', 'Link to internal page'),
                        'SiteTree',
                        'ID',
                        'MenuTitle'
                    )
                )
                    ->displayIf('LinkType')
                    ->isEqualTo('Internal')
                    ->end(),
                DisplayLogicWrapper::create(
                    TextField::create('LinkExternal', _t('ImageSlider.LinkExternal', 'Link to external page'))
                )
                    ->displayIf('LinkType')
                    ->isEqualTo('External')
                    ->end(),
                DisplayLogicWrapper::create(
                    TextField::create('LinkEmail', _t('ImageSlider.LinkEmail', 'Link to email address'))
                )
                    ->displayIf('LinkType')
                    ->isEqualTo('Email')
                    ->end(),
                DisplayLogicWrapper::create(
                    TextField::create('LinkTelephone', _t('ImageSlider.LinkTelephone', 'Link to telephone number'))
                )
                    ->displayIf('LinkType')
                    ->isEqualTo('Telephone')
                    ->end()
            ),
            'LinkType'
        );

        return $fields;
    }

    public function getThumbnail() {
        if ($Image = $this->ImageID) {
            return $this->Image()->SetHeight(50);
        } else {
            return '(No Image)';
        }
    }
}

