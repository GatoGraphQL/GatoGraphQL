<?php
/**
 * Date: 3/24/17
 *
 * @author Volodymyr Rashchepkin <rashepkin@gmail.com>
 */

namespace Youshido\GraphQL\Directive;


class DirectiveLocation
{

    const QUERY = 'QUERY';
    const MUTATION = 'MUTATION';
    const FIELD = 'FIELD';
    const FIELD_DEFINITION = 'FIELD_DEFINITION';
    const FRAGMENT_DEFINITION = 'FRAGMENT_DEFINITION';
    const FRAGMENT_SPREAD = 'FRAGMENT_SPREAD';
    const INLINE_FRAGMENT = 'INLINE_FRAGMENT';
    const ENUM_VALUE = 'ENUM_VALUE';
}
