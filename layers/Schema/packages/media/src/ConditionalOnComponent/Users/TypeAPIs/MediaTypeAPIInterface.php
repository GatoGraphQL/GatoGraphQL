<?php

declare(strict_types=1);

namespace PoPSchema\Media\ConditionalOnComponent\Users\TypeAPIs;

interface MediaTypeAPIInterface
{
    public function getMediaAuthorId(string | int | object $mediaObjectOrID): string | int | null;
}
