<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType;

class RootCreateTagTermInputObjectTypeResolver extends AbstractCreateOrUpdateTagTermInputObjectTypeResolver implements CreateTagTermInputObjectTypeResolverInterface
{
    use RootCreateTagTermInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootCreateTagInput';
    }
}
