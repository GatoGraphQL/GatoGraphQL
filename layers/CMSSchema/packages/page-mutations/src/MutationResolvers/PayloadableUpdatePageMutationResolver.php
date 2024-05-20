<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\MutationResolvers;

class PayloadableUpdatePageMutationResolver extends AbstractCreateUpdatePageMutationResolver
{
    use PayloadablePageMutationResolverTrait;
}
