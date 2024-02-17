<?php

namespace Sakydev\Boring\Services;

use Sakydev\Boring\Models\Collection;
use Sakydev\Boring\Repositories\EntryRepository;

class EntryService
{
    public function __construct(
        readonly EntryRepository $entryRepository,
    ) {}

    public function store(array $content, int $userId): Collection {
        return $this->entryRepository->store($content, $userId);
    }
}
