<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\Facades;

use PoP\Root\App;
use PoPCMSSchema\CustomPostMetaMutations\TypeAPIs\CustomPostMetaTypeMutationAPIInterface;

class CustomPostMetaTypeMutationAPIFacade
{
    public static function getInstance(): CustomPostMetaTypeMutationAPIInterface
    {
        /**
         * @var CustomPostMetaTypeMutationAPIInterface
         */
        $service = App::getContainer()->get(CustomPostMetaTypeMutationAPIInterface::class);
        return $service;
    }
}
