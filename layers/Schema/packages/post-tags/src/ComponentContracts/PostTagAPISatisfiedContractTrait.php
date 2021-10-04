<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\ComponentContracts;

use PoPSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait PostTagAPISatisfiedContractTrait
{
    protected PostTagTypeAPIInterface $postTagTypeAPI;
    protected PostTagObjectTypeResolver $postTagObjectTypeResolver;

    #[Required]
    public function autowirePostTagAPISatisfiedContractTrait(
        PostTagTypeAPIInterface $postTagTypeAPI,
        PostTagObjectTypeResolver $postTagObjectTypeResolver,
    ): void {
        $this->postTagTypeAPI = $postTagTypeAPI;
        $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
    }

    public function getTagTypeAPI(): TagTypeAPIInterface
    {
        return $this->postTagTypeAPI;
    }

    public function getTagTypeResolver(): TagObjectTypeResolverInterface
    {
        return $this->postTagObjectTypeResolver;
    }
}
