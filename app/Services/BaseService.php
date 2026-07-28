<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class BaseService
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records.
     */
    public function all(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Find by ID.
     */
    public function find(string $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Create record.
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update record.
     */
    public function update(string $id, array $data): Model
    {
        $record = $this->find($id);

        $record->update($data);

        return $record;
    }

    /**
     * Soft delete / deactivate.
     */
    public function deactivate(string $id): Model
    {
        $record = $this->find($id);

        $record->update([
            'is_active' => false
        ]);

        return $record;
    }
}
