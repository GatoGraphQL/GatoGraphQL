<?php
namespace PoPSchema\Categories;

interface ObjectPropertyResolver
{
    public function getCategoryID($cat);
    public function getCategorySlug($cat);
}
