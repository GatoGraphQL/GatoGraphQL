<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType;

use PoP\Root\App;
use PoP\GraphQLParser\Component;
use PoP\GraphQLParser\ComponentConfiguration;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use stdClass;

class DirectiveKindEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'DirectiveKindEnum';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return array_merge(
            [
                DirectiveKinds::QUERY,
                DirectiveKinds::SCHEMA,
            ],
            $componentConfiguration->enableComposableDirectives() ? [
                DirectiveKinds::INDEXING,
            ] : [],
        );
    }

    /**
     * Convert the DirectiveKind enum from UPPERCASE as input, to lowercase
     * as defined in DirectiveKinds.php
     */
    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|object
    {
        return parent::coerceValue(strtolower($inputValue));
    }

    /**
     * Convert back from lowercase to UPPERCASE
     */
    public function serialize(string|int|float|bool|object $scalarValue): string|int|float|bool|array
    {
        return strtoupper($scalarValue);
    }
}
