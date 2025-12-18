<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MenuMutations\Constants\MutationInputProperties;
use PoPCMSSchema\Menus\TypeResolvers\InputObjectType\MenuByOneofInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

class CreateMenuFromOneofInputObjectTypeResolver extends AbstractOneofInputObjectTypeResolver
{
    private ?CreateMenuFromContentInputObjectTypeResolver $createMenuFromContentInputObjectTypeResolver = null;
    private ?CreateMenuFromURLInputObjectTypeResolver $createMenuFromURLInputObjectTypeResolver = null;
    private ?MenuByOneofInputObjectTypeResolver $menuByOneofInputObjectTypeResolver = null;

    final protected function getCreateMenuFromContentInputObjectTypeResolver(): CreateMenuFromContentInputObjectTypeResolver
    {
        if ($this->createMenuFromContentInputObjectTypeResolver === null) {
            /** @var CreateMenuFromContentInputObjectTypeResolver */
            $createMenuFromContentInputObjectTypeResolver = $this->instanceManager->getInstance(CreateMenuFromContentInputObjectTypeResolver::class);
            $this->createMenuFromContentInputObjectTypeResolver = $createMenuFromContentInputObjectTypeResolver;
        }
        return $this->createMenuFromContentInputObjectTypeResolver;
    }
    final protected function getCreateMenuFromURLInputObjectTypeResolver(): CreateMenuFromURLInputObjectTypeResolver
    {
        if ($this->createMenuFromURLInputObjectTypeResolver === null) {
            /** @var CreateMenuFromURLInputObjectTypeResolver */
            $createMenuFromURLInputObjectTypeResolver = $this->instanceManager->getInstance(CreateMenuFromURLInputObjectTypeResolver::class);
            $this->createMenuFromURLInputObjectTypeResolver = $createMenuFromURLInputObjectTypeResolver;
        }
        return $this->createMenuFromURLInputObjectTypeResolver;
    }
    final protected function getMenuByOneofInputObjectTypeResolver(): MenuByOneofInputObjectTypeResolver
    {
        if ($this->menuByOneofInputObjectTypeResolver === null) {
            /** @var MenuByOneofInputObjectTypeResolver */
            $menuByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(MenuByOneofInputObjectTypeResolver::class);
            $this->menuByOneofInputObjectTypeResolver = $menuByOneofInputObjectTypeResolver;
        }
        return $this->menuByOneofInputObjectTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'CreateMenuFromInput';
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            MutationInputProperties::MENU_BY => $this->getMenuByOneofInputObjectTypeResolver(),
            MutationInputProperties::URL => $this->getCreateMenuFromURLInputObjectTypeResolver(),
            MutationInputProperties::CONTENTS => $this->getCreateMenuFromContentInputObjectTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::MENU_BY => $this->__('Use the attachment from an existing menu', 'menu-mutations'),
            MutationInputProperties::URL => $this->__('Upload the attachment from a URL', 'menu-mutations'),
            MutationInputProperties::CONTENTS => $this->__('Create the attachment by passing the file name and body', 'menu-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
