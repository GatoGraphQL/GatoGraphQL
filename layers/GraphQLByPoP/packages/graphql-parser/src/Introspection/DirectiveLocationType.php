<?php
/**
 * Date: 3/24/17
 *
 * @author Volodymyr Rashchepkin <rashepkin@gmail.com>
 */

namespace Youshido\GraphQL\Introspection;

use Youshido\GraphQL\Directive\DirectiveLocation;
use Youshido\GraphQL\Type\Enum\AbstractEnumType;

class DirectiveLocationType extends AbstractEnumType
{

    const QUERY = DirectiveLocation::QUERY;
    const MUTATION = DirectiveLocation::MUTATION;
    const FIELD = DirectiveLocation::FIELD;
    const FIELD_DEFINITION = DirectiveLocation::FIELD_DEFINITION;
    const FRAGMENT_DEFINITION = DirectiveLocation::FRAGMENT_DEFINITION;
    const FRAGMENT_SPREAD = DirectiveLocation::FRAGMENT_SPREAD;
    const INLINE_FRAGMENT = DirectiveLocation::INLINE_FRAGMENT;
    const ENUM_VALUE = DirectiveLocation::ENUM_VALUE;

    public function getName()
    {
        return '__DirectiveLocation';
    }

    public function getValues()
    {
        return [
            ['name' => 'QUERY', 'value' => self::QUERY],
            ['name' => 'MUTATION', 'value' => self::MUTATION],
            ['name' => 'FIELD', 'value' => self::FIELD],
            ['name' => 'FIELD_DEFINITION', 'value' => self::FIELD_DEFINITION],
            ['name' => 'FRAGMENT_DEFINITION', 'value' => self::FRAGMENT_DEFINITION],
            ['name' => 'FRAGMENT_SPREAD', 'value' => self::FRAGMENT_SPREAD],
            ['name' => 'INLINE_FRAGMENT', 'value' => self::INLINE_FRAGMENT],
            ['name' => 'ENUM_VALUE', 'value' => self::ENUM_VALUE],
        ];
    }

}
