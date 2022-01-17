<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\Error;

interface ErrorHelperInterface
{
    public function returnResultOrConvertError(mixed $result): mixed;
}
