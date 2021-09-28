<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostMutations\MutationResolvers;

use Symfony\Contracts\Service\Attribute\Required;
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
    protected PostTypeAPIInterface $postTypeAPI;

    #[Required]
    public function autowireAbstractCreateUpdatePostMutationResolver(
        PostTypeAPIInterface $postTypeAPI,
    ): void {
        $this->postTypeAPI = $postTypeAPI;
    }

    public function getCustomPostType(): string
    {
        return $this->postTypeAPI->getPostCustomPostType();
    }
}
