<?php

namespace Loyals\ImageSlider\Model;

use Page;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\CompositeField;
use UncleCheese\DisplayLogic\Forms\Wrapper;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\FieldType\DBBoolean;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Email\Email;
use Sheadawson\DependentDropdown\Forms\DependentDropdownField;

/**
 * Created by PhpStorm.
 * User: jpvanderpoel
 * Date: 14/09/16
 * Time: 14:20
 */
class ImageSlide extends DataObject
{
    private static $table_name = 'ImageSlide';

    /**
     * @inheritdoc
     */
    private static $singular_name = 'Image Slide';

    /**
     * @inheritdoc
     */
    private static $plural_name = 'Image Slides';

    /**
     * @inheritdoc
     */
    private static $db = [
        'Name'            => 'Varchar(255)',
        'Title'           => 'Varchar(255)',
        'SubTitle'        => 'Varchar(255)',
        'ButtonText'      => 'Varchar(255)',
        'LinkType'        => 'Enum("None,Internal,BlogPost,External,Email,Telephone","None")',
        'LinkExternal'    => 'Varchar(255)',
        'LinkEmail'       => 'Varchar(255)',
        'LinkTelephone'   => 'Varchar(255)',
        'OpenInNewWindow' => DBBoolean::class,
        'Enabled'         => DBBoolean::class,
    ];

    /**
     * @inheritdoc
     */
    private static $has_one = [
        'Image'    => Image::class,
        'Page'     => Page::class,
        'Blog'     => Page::class,
        'BlogPost' => Page::class,
    ];

    /**
     * @inheritdoc
     */
    private static $summary_fields = [
        'Thumbnail' => Image::class,
    ];

    /**
     * @param null $member
     *
     * @return bool|int
     */
    public function canView($member = null) {
        return Permission::check('CMS_ACCESS', 'any', $member);
    }

    /**
     * @param null $member
     *
     * @return bool|int
     */
    public function canEdit($member = null) {
        return Permission::check('CMS_ACCESS', 'any', $member);
    }

    /**
     * @param null $member
     *
     * @return bool|int
     */
    public function canDelete($member = null) {
        return Permission::check('CMS_ACCESS', 'any', $member);
    }

    /**
     * @param null $member
     *
     * @return bool|int
     */
    public function canCreate($member = null, $context = []) {
        return Permission::check('CMS_ACCESS', 'any', $member);
    }

    /**
     * @inheritdoc
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
            'LinkExternal',
            'Image',
            'PageID',
            'BlogID',
            'BlogPostID',
            'LinkEmail',
            'LinkTelephone',
            'OpenInNewWindow',
            'Enabled'
        ]);

        $fields->insertBefore(
            'ButtonText',
            UploadField::create('Image', _t('ImageSlider.Image', Image::class))
                ->setFolderName('image-slider-images')
        );

        $fields->insertBefore(
            'Name',
            CheckboxField::create('Enabled', _t('ImageSlider.Enabled', 'Enabled'))
        );

        if (class_exists('SilverStripe\Blog\Model\BlogPost')) {
            $blogDatesSource = function ($blogID) {
                if ($blogID) {
                    return \SilverStripe\Blog\Model\BlogPost::get()->filter('ParentID', $blogID)->map();
                }

                return [];
            };
        } else {
            $linkTypeField = $fields->dataFieldByName('LinkType');
            $options = $this->dbObject('LinkType')->enumValues();
            unset($options['BlogPost']);
            $linkTypeField->setSource($options);
        }

        $fields->insertAfter(
            'LinkType',
            CompositeField::create(
                Wrapper::create(
                    TreeDropdownField::create(
                        'PageID',
                        _t('ImageSlider.LinkInternal', 'Link to internal page'),
                        SiteTree::class,
                        'ID',
                        'MenuTitle'
                    )->setShowSelectedPath(true)
                )
                    ->displayIf('LinkType')
                    ->isEqualTo('Internal')
                    ->end(),
                class_exists('SilverStripe\Blog\Model\Blog') ? (Wrapper::create(
                    $blogSource = DropdownField::create(
                        'BlogID',
                        _t('ImageSlider.Blog', 'Choose parent blog'),
                        \SilverStripe\Blog\Model\Blog::get()->map()
                    )->setHasEmptyDefault(true)
                )
                    ->displayIf('LinkType')
                    ->isEqualTo('BlogPost')
                    ->end()
                ) : null,
                class_exists('SilverStripe\Blog\Model\BlogPost') ? (Wrapper::create(
                    DependentDropdownField::create(
                        'BlogPostID',
                        _t('ImageSlider.BlogPost', 'Link to blog post'),
                        $blogDatesSource
                    )->setDepends($blogSource)
                )
                    ->displayIf('LinkType')
                    ->isEqualTo('BlogPost')
                    ->andIf('BlogID')
                    ->isNotEmpty()
                    ->end()
                ) : null,
                Wrapper::create(
                    TextField::create('LinkExternal', _t('ImageSlider.LinkExternal', 'Link to external page'))
                )
                    ->displayIf('LinkType')
                    ->isEqualTo('External')
                    ->end(),
                Wrapper::create(
                    TextField::create('LinkEmail', _t('ImageSlider.LinkEmail', 'Link to email address'))
                )
                    ->displayIf('LinkType')
                    ->isEqualTo(Email::class)
                    ->end(),
                Wrapper::create(
                    TextField::create('LinkTelephone', _t('ImageSlider.LinkTelephone', 'Link to telephone number'))
                )
                    ->displayIf('LinkType')
                    ->isEqualTo('Telephone')
                    ->end(),
                Wrapper::create(
                    CheckboxField::create('OpenInNewWindow', _t('ImageSlider.OpenInNewWindow', 'Open in new window'))
                )
                    ->displayIf('LinkType')
                    ->isEqualTo('Internal')
                        ->orIf('LinkType')
                        ->isEqualTo('BlogPost')
                            ->orIf('LinkType')
                            ->isEqualTo('External')
                    ->end()
            )
        );

        $this->extend('modifyCMSFields', $fields);

        return $fields;
    }

    /**
     * @Todo: better description
     *
     * @return string
     */
    public function getThumbnail() {
        if ($Image = $this->ImageID) {
            return $this->Image()->ScaleHeight(50);
        } else {
            return '(No Image)';
        }
    }
}
