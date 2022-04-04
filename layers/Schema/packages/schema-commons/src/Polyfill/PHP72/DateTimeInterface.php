<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\Polyfill\PHP72;

interface DateTimeInterface
{
    /**
     * @see https://www.php.net/manual/en/class.datetimeinterface.php#datetime.constants.atom
     */
    public final const ATOM = 'Y-m-d\TH:i:sP';
}
