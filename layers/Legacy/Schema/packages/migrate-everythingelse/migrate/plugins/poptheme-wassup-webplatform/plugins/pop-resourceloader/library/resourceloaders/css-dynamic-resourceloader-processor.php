<?php

class PoPThemeWassup_DynamicCSSResourceLoaderProcessor extends PoP_DynamicCSSResourceLoaderProcessor
{
    public final const RESOURCE_CSS_BACKGROUNDIMAGE = 'background-image';
    public final const RESOURCE_CSS_FEEDTHUMB = 'feed-thumb';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_CSS_BACKGROUNDIMAGE],
            [self::class, self::RESOURCE_CSS_FEEDTHUMB],
        ];
    }
    
    public function getFilename(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_CSS_BACKGROUNDIMAGE:
                global $popthemewassup_backgroundimage_file;
                return $popthemewassup_backgroundimage_file->getFilename();

            case self::RESOURCE_CSS_FEEDTHUMB:
                global $popthemewassup_feedthumb_file;
                return $popthemewassup_feedthumb_file->getFilename();
        }

        return parent::getFilename($resource);
    }
    
    public function getDir(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_CSS_BACKGROUNDIMAGE:
                global $popthemewassup_backgroundimage_file;
                return $popthemewassup_backgroundimage_file->getDir();

            case self::RESOURCE_CSS_FEEDTHUMB:
                global $popthemewassup_feedthumb_file;
                return $popthemewassup_feedthumb_file->getDir();
        }
    
        return parent::getDir($resource);
    }
    
    public function getFileUrl(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_CSS_BACKGROUNDIMAGE:
                global $popthemewassup_backgroundimage_file;
                return $popthemewassup_backgroundimage_file->getFileurl();

            case self::RESOURCE_CSS_FEEDTHUMB:
                global $popthemewassup_feedthumb_file;
                return $popthemewassup_feedthumb_file->getFileurl();
        }

        return parent::getFileUrl($resource);
    }
}


