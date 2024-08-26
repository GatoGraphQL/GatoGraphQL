<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType\RootUpdateTagTermInputObjectTypeResolverTrait;

class RootUpdateGenericTagTermInputObjectTypeResolver extends AbstractCreateOrUpdateGenericTagTermInputObjectTypeResolver implements UpdateGenericTagTermInputObjectTypeResolverInterface
{
    use RootUpdateTagTermInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootUpdateGenericTagInput';
    }

    protected function isTaxonomyInputFieldMandatory(): bool
    {
        return false;
    }
}
