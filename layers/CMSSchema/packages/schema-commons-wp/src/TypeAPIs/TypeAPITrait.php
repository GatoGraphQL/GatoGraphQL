<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommonsWP\TypeAPIs;

use function esc_sql;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
trait TypeAPITrait
{
    /**
     * Only keep the single call to the CMS function and
     * no extra logic whatsoever.
     *
     * Overridable by Faker tests.
     */
    protected function resolveEscSQL(string $string): string
    {
        return esc_sql($string);
    }
}
