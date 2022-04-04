<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\Constants;

/**
 * Same list as defined for WordPress
 *
 * @see https://developer.wordpress.org/reference/classes/wp_term_query/__construct/#parameters
 */
class TaxonomyOrderBy
{
    public final const NAME = 'NAME';
    public final const SLUG = 'SLUG';
    public final const ID = 'ID';
    public final const PARENT = 'PARENT';
    public final const COUNT = 'COUNT';
    public final const NONE = 'NONE';
    public final const INCLUDE = 'INCLUDE';
    public final const SLUG__IN = 'SLUG__IN';
    public final const DESCRIPTION = 'DESCRIPTION';

    // public final const TERM_GROUP = 'TERM_GROUP';
    // public final const TERM_ID = 'TERM_ID';
    // public final const TERM_ORDER = 'TERM_ORDER';
}
