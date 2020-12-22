<?php
namespace PoPSchema\Pages;

interface ObjectPropertyResolver
{
    public function getPageId($page);
    public function getPageModified($page);
}
