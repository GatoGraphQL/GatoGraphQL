<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

class SchemaDefinitionTypes
{
    /**
     * Custom scalar type "comprising" the 5 built-in scalar types by GraphQL:
     *
     * - String
     * - Int
     * - Float
     * - Bool
     * - ID
     *
     * @see https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars
     *
     * To be more correct, the name should be `ANY_BUILT_IN_SCALAR`,
     * but `ANY_SCALAR` is used instead for convenience.
     *
     * This type is a hack, to address a defficiency in the GraphQL spec:
     * In GraphQL there is no union of scalars.
     *
     * @see https://github.com/graphql/graphql-spec/issues/215
     *
     * This type comes to represent any of the built-in scalars, to be used
     * when we do not know of what actual scalar type will the value be.
     *
     * Eg: when calling `get_option` or `get_post_meta` in WordPress,
     * which may return a bool, or int, or string. The developer will know,
     * but the schema doesn't know.
     *
     * In GraphQL clients (such as GraphiQL), errors will be shown
     * when providing a `String` to an input of type `ANY_SCALAR`,
     * but the GraphQL server will process the value correctly.
     */
    const TYPE_ANY_SCALAR = 'AnyScalar';

    /**
     * Custom scalar type representing an `object` from PHP:
     * some instance from a class or stdClass.
     *
     * It also represents a JSONObject input.
     *
     * Please notice: this type is not an `array`, however it can be represented
     * through an array on the server-side via PHP. The distinction is important,
     * because an `array` is not a type in GraphQL, but an `object` can be, as a custom scalar
     */
    const TYPE_OBJECT = 'Object';

    /**
     * This custom scalar type comprises the 5 built-in scalar types by GraphQL
     * (represented also by `ANY_SCALAR`), plus the Object type
     * (represented also by `OBJECT`), plus any custom scalar
     * (such as Date, or any other).
     *
     * Because it comprises the Object type, this custom scalar type can
     * also be represented through an array on the server-side via PHP.
     */
    const TYPE_MIXED = 'Mixed';

    /**
     * This custom scalar type comprises the 2 atomic types by GraphQL:
     *
     * - String
     * - Int
     *
     * It is used to represent array keys, which can only be numeric or strings.
     */
    const TYPE_ARRAY_KEY = 'ArrayKey';

    /**
     * One of the 5 atomic scalars in GraphQL
     */
    const TYPE_STRING = 'String';
    /**
     * One of the 5 atomic scalars in GraphQL
     */
    const TYPE_INT = 'Int';
    /**
     * One of the 5 atomic scalars in GraphQL
     */
    const TYPE_FLOAT = 'Float';
    /**
     * One of the 5 atomic scalars in GraphQL
     */
    const TYPE_BOOL = 'Boolean';
    /**
     * One of the 5 atomic scalars in GraphQL
     */
    const TYPE_ID = 'ID';

    /**
     * Custom scalars
     */
    const TYPE_DATE = 'Date';
    const TYPE_TIME = 'Time';
    const TYPE_URL = 'URL';
    const TYPE_EMAIL = 'Email';
    const TYPE_IP = 'IP';
    const TYPE_ENUM = 'Enum';
    const TYPE_INPUT_OBJECT = 'InputObject';
}
