<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType\RootDeleteTagTermInputObjectTypeResolverTrait;

class RootDeleteGenericTagTermInputObjectTypeResolver extends AbstractDeleteGenericTagTermInputObjectTypeResolver implements DeleteGenericTagTermInputObjectTypeResolverInterface
{
    use RootDeleteTagTermInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootDeleteGenericTagInput';
    }
}
