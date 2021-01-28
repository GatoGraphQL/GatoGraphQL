<?php
namespace PoPSchema\Pages\WP;

class Utils
{
    protected static $cmsToPoPPageStatusConversion = [
        'publish' => \PoPSchema\CustomPosts\Types\Status::PUBLISHED,
        'pending' => \PoPSchema\CustomPosts\Types\Status::PENDING,
        'draft' => \PoPSchema\CustomPosts\Types\Status::DRAFT,
        'trash' => \PoPSchema\CustomPosts\Types\Status::TRASH,
    ];
    protected static $popToCMSPageStatusConversion;

    public static function init(): void
    {
        self::$popToCMSPageStatusConversion = array_flip(self::$cmsToPoPPageStatusConversion);
    }

    public static function convertPageStatusFromCMSToPoP($status)
    {
        // Convert from the CMS status to PoP's one
        return self::$cmsToPoPPageStatusConversion[$status];
    }
    public static function convertPageStatusFromPoPToCMS($status)
    {
        // Convert from the CMS status to PoP's one
        return self::$popToCMSPageStatusConversion[$status];
    }
    public static function getCMSPageStatuses()
    {
        return array_keys(self::$cmsToPoPPageStatusConversion);
    }
}

/**
 * Initialize
 */
Utils::init();
