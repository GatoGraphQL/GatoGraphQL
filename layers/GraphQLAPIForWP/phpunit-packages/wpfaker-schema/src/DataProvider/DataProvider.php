<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema\DataProvider;

class DataProvider implements DataProviderInterface
{
    /**
     * The parsed WordPress data from a pre-defined export file.
     *
     * @return array<string,mixed>
     */
    public function getFixedDataset(): array
    {
        return [];
    }
}
