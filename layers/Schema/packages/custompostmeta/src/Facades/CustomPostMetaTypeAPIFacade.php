<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMeta\Facades;

use PoP\Engine\App;
use PoPSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface;

class CustomPostMetaTypeAPIFacade
{
    public static function getInstance(): CustomPostMetaTypeAPIInterface
    {
        /**
         * @var CustomPostMetaTypeAPIInterface
         */
        $service = App::getContainer()->get(CustomPostMetaTypeAPIInterface::class);
        return $service;
    }
}
