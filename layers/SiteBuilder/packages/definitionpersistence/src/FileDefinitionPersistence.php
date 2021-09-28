<?php

declare(strict_types=1);

namespace PoP\DefinitionPersistence;

use PoP\FileStore\File\AbstractFile;
use PoP\FileStore\Store\FileStoreInterface;
use PoP\Definitions\AbstractDefinitionPersistence;

class FileDefinitionPersistence extends AbstractDefinitionPersistence
{
    public function __construct(
        protected FileStoreInterface $fileStore,
        protected AbstractFile $file
    ) {
        parent::__construct();
    }

    protected function getPersistedData(): array
    {
        return (array)$this->fileStore->get($this->file);
    }

    protected function persist(array $data): void
    {
        $this->fileStore->save($this->file, $data);
    }
}
