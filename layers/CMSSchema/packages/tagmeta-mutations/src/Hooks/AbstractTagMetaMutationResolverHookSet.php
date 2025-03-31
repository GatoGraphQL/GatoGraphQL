<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\Hooks;

use PoPCMSSchema\TagMutations\Constants\TagCRUDHookNames;
use PoPCMSSchema\TaxonomyMetaMutations\Hooks\AbstractTaxonomyMetaMutationResolverHookSet;

abstract class AbstractTagMetaMutationResolverHookSet extends AbstractTaxonomyMetaMutationResolverHookSet
{
    protected function getErrorPayloadHookName(): string
    {
        return TagCRUDHookNames::ERROR_PAYLOAD;
    }
}
