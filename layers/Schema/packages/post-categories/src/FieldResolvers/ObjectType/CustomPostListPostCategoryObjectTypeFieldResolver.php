<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\FieldResolvers\ObjectType;

use PoP\Translation\TranslationAPIInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoPSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPSchema\Categories\FieldResolvers\ObjectType\AbstractCustomPostListCategoryObjectTypeFieldResolver;
use PoPSchema\PostCategories\ComponentContracts\PostCategoryAPISatisfiedContractTrait;

class CustomPostListPostCategoryObjectTypeFieldResolver extends AbstractCustomPostListCategoryObjectTypeFieldResolver
{
    use PostCategoryAPISatisfiedContractTrait;

    public function isServiceEnabled(): bool
    {
        /**
         * @todo Enable if the post category (i.e. taxonomy "category") can have other custom post types use it (eg: page, event, etc)
         */
        return false;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostCategoryObjectTypeResolver::class,
        ];
    }

    protected function getQueryProperty(): string
    {
        return 'category-ids';
    }
}
