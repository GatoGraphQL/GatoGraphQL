<?php
namespace PoPSchema\Tags;

interface ObjectPropertyResolver
{
    public function getTagName($tag);
    public function getTagSlug($tag);
    public function getTagDescription($tag);
    public function getTagParent($tag);
    public function getTagCount($tag);
    public function getTagID($tag);
}
