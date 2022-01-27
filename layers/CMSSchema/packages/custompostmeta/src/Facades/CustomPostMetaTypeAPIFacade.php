<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMeta\Facades;

use PoP\Root\App;
use PoPCMSSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface;

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
