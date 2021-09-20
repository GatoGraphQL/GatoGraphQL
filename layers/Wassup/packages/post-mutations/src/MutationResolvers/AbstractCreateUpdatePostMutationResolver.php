<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostMutations\MutationResolvers;

use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CustomPostMutations\TypeAPIs\CustomPostTypeMutationAPIInterface;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver;
use PoPSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoPSitesWassup\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;

abstract class AbstractCreateUpdatePostMutationResolver extends AbstractCreateUpdateCustomPostMutationResolver
{
    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver,
        NameResolverInterface $nameResolver,
        UserRoleTypeAPIInterface $userRoleTypeAPI,
        CustomPostTypeAPIInterface $customPostTypeAPI,
        CustomPostTypeMutationAPIInterface $customPostTypeMutationAPI,
        PostCategoryTypeAPIInterface $postCategoryTypeAPI,
        protected PostTypeAPIInterface $postTypeAPI,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $customPostStatusEnumTypeResolver,
            $nameResolver,
            $userRoleTypeAPI,
            $customPostTypeAPI,
            $customPostTypeMutationAPI,
            $postCategoryTypeAPI,
        );
    }

    public function getCustomPostType(): string
    {
        return $this->postTypeAPI->getPostCustomPostType();
    }
}
