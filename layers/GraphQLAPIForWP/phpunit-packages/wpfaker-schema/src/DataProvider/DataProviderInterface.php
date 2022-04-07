<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema\DataProvider;

interface DataProviderInterface
{
    /**
     * The parsed WordPress data from a pre-defined export file.
     *
     * @return array<string,mixed>
     */
    public function getFixedDataset(): array;
}
