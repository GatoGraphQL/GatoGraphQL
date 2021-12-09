<?php

declare(strict_types=1);

namespace PoPSchema\Meta\TypeAPIs;

interface MetaTypeAPIInterface
{
    /**
     * @return string[]
     */
    public function getAllowOrDenyMetaEntries(): array;
    public function getAllowOrDenyMetaBehavior(): string;
}
