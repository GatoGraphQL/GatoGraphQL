<?php

declare(strict_types=1);

namespace PoP\DefinitionPersistence;

use PoP\FileStore\File\AbstractFile;
use PoP\FileStore\Store\FileStoreInterface;
use PoP\Definitions\AbstractDefinitionPersistence;

class FileDefinitionPersistence extends AbstractDefinitionPersistence
{
    protected FileStoreInterface $fileStore;
    protected AbstractFile $file;

    public function __construct(
        FileStoreInterface $fileStore,
        AbstractFile $file
    ) {
        $this->fileStore = $fileStore;
        $this->file = $file;
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
