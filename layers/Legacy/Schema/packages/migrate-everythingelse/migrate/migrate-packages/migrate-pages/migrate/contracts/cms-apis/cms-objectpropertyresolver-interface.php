<?php
namespace PoPCMSSchema\Pages;

interface ObjectPropertyResolver
{
    public function getPageModified($page);
    /**
     * Get the ID of the static page for the homepage
     * Returns an ID (int? string?) or null
     */
    public function getHomeStaticPageID(): string | int;
}
