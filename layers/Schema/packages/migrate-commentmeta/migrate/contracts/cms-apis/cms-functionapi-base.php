<?php
namespace PoPSchema\CommentMeta;

abstract class FunctionAPI_Base implements FunctionAPI
{
    public function __construct()
    {
        FunctionAPIFactory::setInstance($this);
    }
    public function getMetaKey($meta_key)
    {
        return $meta_key;
    }
}
