<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractWithParentCustomPostsFilterInputObjectTypeResolver;

abstract class AbstractPagesFilterInputObjectTypeResolver extends AbstractWithParentCustomPostsFilterInputObjectTypeResolver implements PagesFilterInputObjectTypeResolverInterface
{
    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'parentID' => $this->__('Filter pages with the given parent IDs. \'0\' means \'no parent\'', 'pages'),
            'parentIDs' => $this->__('Filter pages with the given parent ID. \'0\' means \'no parent\'', 'pages'),
            'excludeParentIDs' => $this->__('Exclude pages with the given parent IDs', 'pages'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
