<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\Hooks;

use PoPCMSSchema\CategoryMutations\Constants\CategoryCRUDHookNames;
use PoPCMSSchema\CustomPostMetaMutations\Hooks\AbstractCustomPostMetaMutationResolverHookSet;

abstract class AbstractCategoryMetaMutationResolverHookSet extends AbstractCustomPostMetaMutationResolverHookSet
{
    protected function getErrorPayloadHookName(): string
    {
        return CategoryCRUDHookNames::ERROR_PAYLOAD;
    }
}
