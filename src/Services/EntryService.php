<?php

namespace Sakydev\Boring\Services;

use Sakydev\Boring\Exceptions\BadRequestException;
use Sakydev\Boring\Exceptions\NotFoundException;
use Sakydev\Boring\Models\Entry;
use Sakydev\Boring\Models\Field;
use Sakydev\Boring\Repositories\EntryRepository;

class EntryService
{
    private const FIELD_REQUIRED = 'required';
    private const FIELD_UNIQUE = 'unique';
    private const FIELD_MAXIMUM_LENGTH = 'maximumLength';
    private const FIELD_MINIMUM_LENGTH = 'minimumLength';

    public function __construct(
        readonly CollectionService $collectionService,
        readonly FieldService $fieldService,
        readonly EntryRepository $entryRepository,
    ) {}

    /**
     * @throws NotFoundException
     */
    public function store(array $content, string $collectionName): Entry {
        $collectionDetails = $this->collectionService->getByName($collectionName);
        if (!$collectionDetails) {
            throw new NotFoundException('item.error.collection.notFound');
        }

        // dynamically set entry table name
        $this->entryRepository->setTableName($collectionName);

        $fields = $collectionDetails->fields()->get();
        foreach ($fields as $field) {
            if (!empty($field->validation)) {
                $fieldContent = $content[$field->name] ?? null;
                $validationError = $this->validateContent($fieldContent, $field);

                if ($validationError) {
                    throw new BadRequestException($validationError);
                }
            }
        }

        return $this->entryRepository->store($content);
    }

    private function validateContent(?string $content, Field $field): ?string {
        $rules = $field->validation;

        if ($field->is_required && empty($content)) {
            return $this->validationError($field->name, self::FIELD_REQUIRED);
        }

        if (!empty($rules['unique'])) {
            if ($this->entryRepository->hasDuplicateEntry($field->name, $content)) {
                return $this->validationError($field->name, self::FIELD_UNIQUE);
            }
        }

        return null;
    }

    private function validationError(string $name, string $type): string {
        $error = '';

        switch ($type) {
            case self::FIELD_REQUIRED:
                $error = sprintf('[%s] is required but was empty', $name);

                break;
            case self::FIELD_UNIQUE:
                $error = sprintf('[%s] has to be unique', $name);

                break;
            default:
                //
        }

        return $error;
    }
}
