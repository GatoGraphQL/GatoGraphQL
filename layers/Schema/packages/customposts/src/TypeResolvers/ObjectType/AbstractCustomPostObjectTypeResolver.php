<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;

abstract class AbstractCustomPostObjectTypeResolver extends AbstractObjectTypeResolver implements CustomPostObjectTypeResolverInterface
{
    protected CustomPostTypeAPIInterface $customPostTypeAPI;

    #[Required]
    public function autowireAbstractCustomPostObjectTypeResolver(
        CustomPostTypeAPIInterface $customPostTypeAPI,
    ): void {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a custom post', 'customposts');
    }

    public function getID(object $object): string | int | null
    {
        return $this->customPostTypeAPI->getID($object);
    }
}
