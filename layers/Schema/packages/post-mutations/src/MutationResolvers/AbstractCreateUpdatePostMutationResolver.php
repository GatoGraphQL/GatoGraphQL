<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\MutationResolvers;

use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPSchema\CustomPostMutations\TypeAPIs\CustomPostTypeMutationAPIInterface;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;

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
        );
    }

    public function getCustomPostType(): string
    {
        return $this->postTypeAPI->getPostCustomPostType();
    }
}
