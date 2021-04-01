<?php
namespace PoPSchema\Categories;

interface ObjectPropertyResolver
{
    public function getCategoryID($cat);
    public function getCategorySlug($cat);
    public function getCategoryDescription($cat);
    public function getCategoryCount($cat);
}
