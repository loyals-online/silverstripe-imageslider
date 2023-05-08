<?php

namespace Loyals\ImageSlider\Service;

use ReflectionClass;
use ReflectionException;

/**
 * Class SiteHelper
 * @author Terry Duivesteijn <terry@loungeroom.nl>
 * @author Gabrijel Gavranović <gabrijel@gavro.nl>
 */
class SiteHelper
{
    /**
     * @var SiteHelper
     */
    private static $instance;

    /** @var null|SiteConfig */
    private $config = null;

    /**
     * @return SiteHelper
     */
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $className
     * @return string
     */
    public static function ClassShortName($className)
    {
        try {
            $reflect = new ReflectionClass($className ?? '');
            return $reflect->getShortName();
        } catch (ReflectionException $e) {
        }

        // fallback
        $parts = explode('\\', $className ?? '');
        return array_shift($parts);
    }
}
