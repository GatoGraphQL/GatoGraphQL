<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\HelperServices;

interface StringFunctionHelperServiceInterface
{
    /**
     * PHP 7.2 does not quote `#`, but PHP 7.3 onwards does.
     * Hence, add this backward compatibility for when running 7.2.
     *
     * @see https://www.php.net/manual/en/function.preg-quote.php#refsect1-function.preg-quote-changelog
     */
    public function quoteRegex(
        string $string,
        ?string $delimiter = null,
    ): string;
}
