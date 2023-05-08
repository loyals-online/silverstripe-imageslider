<?php

namespace Loyals\ImageSlider\Extensions;

use Loyals\ImageSlider\Service\SiteHelper;
use Loyals\ImageSlider\Model\ImageSlide;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\ORM\DataList;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use Symbiote\GridFieldExtensions\GridFieldEditableColumns;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\CompositeField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Forms\GridField\GridFieldButtonRow;

/**
 * Created by PhpStorm.
 * User: jpvanderpoel
 * Date: 14/09/16
 * Time: 14:51
 */
class ImageSliderPageExtension extends DataExtension
{
    /**
     * @inheritdoc
     */
    private static $db = [
        'YoutubeLink' => 'Varchar(255)',
    ];

    /**
     * @inheritdoc
     */
    private static $has_one = [
        'SingleHeaderImage' => Image::class,
    ];

    /**
     * @inheritdoc
     */
    private static $many_many = [
        'ImageSlides' => ImageSlide::class,
    ];

    /**
     * @inheritdoc
     */
    private static $many_many_extraFields = [
        'ImageSlides' => ['SortOrder' => 'Int'],
    ];

    /**
     * @inheritdoc
     */
    function fieldLabels($includerelations = true)
    {
        $labels = $this->owner->fieldLabels($includerelations);

        $labels['ImageSlides'] = _t('ImageSlider.ImageSlides', 'Image Slides');

        return $labels;
    }

    /**
     * @inheritdoc
     */
    public function updateCMSFields(FieldList $fields)
    {

        if($this->hasImageSlider()) {

            $editableFields = [
                'Name'    => [
                    'title' => _t('ImageSlider.Name', 'Name'),
                    'field' => ReadonlyField::class,
                ],
                'Title'   => [
                    'title' => _t('ImageSlider.Title', 'Title'),
                    'field' => ReadonlyField::class,
                ],
                'Enabled' => [
                    'title'    => _t('ImageSlider.Enabled', 'Enabled'),
                    'callback' => function ($record, $column, $grid) {
                        return CheckboxField::create($column, '');
                    },
                ],
            ];

            $config = GridFieldConfig_RecordEditor::create()
                ->removeComponentsByType(GridFieldDeleteAction::class)
                ->removeComponentsByType(GridFieldDataColumns::class)
                ->addComponent(new GridFieldOrderableRows('SortOrder'))
                ->addComponent(new GridFieldDataColumns(), GridFieldButtonRow::class)
                ->addComponent((new GridFieldEditableColumns())->setDisplayFields($editableFields), GridFieldButtonRow::class)
                ->addComponent(new GridFieldDeleteAction(false));

            $gridfield = GridField::create('ImageSlides', _t('ImageSlider.ImageSlides', 'Image Slides'), $this->owner->ImageSlides()
                ->sort('SortOrder ASC'), $config);

            $fields->addFieldsToTab("Root.Image Slider", [
                $gridfield,
                CompositeField::create(
                    TextField::create(
                        'YoutubeLink',
                        _t('ImageSlide.db_YoutubeLink', 'Youtube link'),
                        $this->owner->YoutubeLink
                    )
                ),
                ReadonlyField::create(
                    'YoutubeExplanation',
                    ' ',
                    _t('ImageSlide.YoutubeExplanation', 'Youtube link restricts effective slides')
                ),
            ]);
        } elseif ($this->hasSingleImage()){
            $fields->addFieldsToTab('Root.Main', [
                UploadField::create('SingleHeaderImage', _t('ImageSlider.Image', 'Image'))->setFolderName('headerimages')
            ], 'Content');
        }

        return $fields;
    }

    /**
     * Retrieve multiple slides
     *
     * @return bool|DataList
     */
    public function MultipleSlides()
    {
        if ($this->owner->YoutubeLink) {
            return false;
        }

        return $this->owner->ImageSlides()
            ->filter(['Enabled' => 1])
            ->sort('SortOrder');
    }

    /**
     * Retrieve single slide
     *
     * @return Image
     */
    public function SingleImage()
    {
        if ($this->owner->YoutubeLink || $this->owner->SingleHeaderImageID) {
            return $this->owner->SingleHeaderImage();
        }
    }

    public function hasImageSlider(){
        $className = SiteHelper::ClassShortName(get_class($this->owner));
        return in_array($className, SiteConfig::current_site_config()->getImageSliderPageTypesArray());
    }

    public function hasSingleImage(){
        $className = SiteHelper::ClassShortName(get_class($this->owner));
        return in_array($className, SiteConfig::current_site_config()->getSingleImagePageTypesArray());
    }

    /**
     * Retrieve the youtube ID from the stored link
     *
     * @return string
     */
    public function YoutubeID()
    {
        return preg_replace('#(https?:\/\/)?(www\.youtube\.com\/embed\/|www\.youtube\.com\/watch\?v=|www\.youtube\.com\/v\/|youtu.be/)#', '', $this->owner->YoutubeLink);

    }
}
