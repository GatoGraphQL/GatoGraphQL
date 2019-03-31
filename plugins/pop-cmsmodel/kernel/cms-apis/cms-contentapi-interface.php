<?php
namespace PoP\CMSModel;

interface ContentAPI extends \PoP\CMS\ContentAPI
{

    public function autoembed($content);
}
