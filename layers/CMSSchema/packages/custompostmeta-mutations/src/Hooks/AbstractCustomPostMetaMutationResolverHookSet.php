<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\Hooks;

use PoPCMSSchema\CustomPostMutations\Constants\CustomPostCRUDHookNames;
use PoPCMSSchema\CustomPostMetaMutations\Hooks\AbstractCustomPostMetaMutationResolverHookSet;

abstract class AbstractCustomPostMetaMutationResolverHookSet extends AbstractCustomPostMetaMutationResolverHookSet
{
    protected function getErrorPayloadHookName(): string
    {
        return CustomPostCRUDHookNames::ERROR_PAYLOAD;
    }
}
