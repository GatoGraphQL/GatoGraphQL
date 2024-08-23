<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType\RootCreateTagTermInputObjectTypeResolverTrait;

class RootCreateGenericTagTermInputObjectTypeResolver extends AbstractCreateOrUpdateGenericTagTermInputObjectTypeResolver implements CreateGenericTagTermInputObjectTypeResolverInterface
{
    use RootCreateTagTermInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootCreateGenericTagInput';
    }
}
