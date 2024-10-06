<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject;

final class OptionValues
{
    /**
     * Watch out! This const can be provided via a secret in GitHub Actions
     *
     * @var string
     */
    public final const EXTENSION = 'extension';

    /**
     * Watch out! This const can be provided via a secret in GitHub Actions
     *
     * @var string
     */
    public final const BUNDLE = 'bundle';
}
