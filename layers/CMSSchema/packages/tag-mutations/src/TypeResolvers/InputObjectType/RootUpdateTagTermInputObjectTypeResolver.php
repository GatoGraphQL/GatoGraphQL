<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType;

class RootUpdateTagTermInputObjectTypeResolver extends AbstractCreateOrUpdateTagTermInputObjectTypeResolver implements UpdateTagTermInputObjectTypeResolverInterface
{
    use RootUpdateTagTermInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootUpdateTagInput';
    }
}
