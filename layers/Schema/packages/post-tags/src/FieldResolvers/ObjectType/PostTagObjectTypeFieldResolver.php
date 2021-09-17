<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\FieldResolvers\ObjectType;

use PoP\Translation\TranslationAPIInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoPSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPSchema\Tags\FieldResolvers\ObjectType\AbstractTagObjectTypeFieldResolver;
use PoPSchema\PostTags\ComponentContracts\PostTagAPISatisfiedContractTrait;

class PostTagObjectTypeFieldResolver extends AbstractTagObjectTypeFieldResolver
{
    use PostTagAPISatisfiedContractTrait;

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostTagObjectTypeResolver::class,
        ];
    }
}
