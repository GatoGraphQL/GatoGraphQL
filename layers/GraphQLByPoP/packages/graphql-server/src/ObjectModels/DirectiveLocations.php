<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

class DirectiveLocations
{
    public final const QUERY = 'QUERY';
    public final const MUTATION = 'MUTATION';
    public final const SUBSCRIPTION = 'SUBSCRIPTION';
    public final const FIELD = 'FIELD';
    public final const FRAGMENT_DEFINITION = 'FRAGMENT_DEFINITION';
    public final const FRAGMENT_SPREAD = 'FRAGMENT_SPREAD';
    public final const INLINE_FRAGMENT = 'INLINE_FRAGMENT';
    public final const SCHEMA = 'SCHEMA';
    public final const SCALAR = 'SCALAR';
    public final const OBJECT = 'OBJECT';
    public final const FIELD_DEFINITION = 'FIELD_DEFINITION';
    public final const ARGUMENT_DEFINITION = 'ARGUMENT_DEFINITION';
    public final const INTERFACE = 'INTERFACE';
    public final const UNION = 'UNION';
    public final const ENUM = 'ENUM';
    public final const ENUM_VALUE = 'ENUM_VALUE';
    public final const INPUT_OBJECT = 'INPUT_OBJECT';
    public final const INPUT_FIELD_DEFINITION = 'INPUT_FIELD_DEFINITION';
}
