<?php

declare(strict_types=1);

namespace app\Repositories;

use app\Repositories\Contracts\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements EloquentRepositoryInterface
{
    public function __construct(
        protected Model $model,
    )
    {
    }

    public function find(int $id): ?Model
    {
        return $this->model->newQuery()->find($id);
    }

    public function findBy(
        array $criteria,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): ?Collection
    {
        $query = $this->model->newQuery();
        foreach ($criteria as $field => $value) {
            $query->where($field, $value);
        }
        if (!empty($orderBy)) {
            foreach ($orderBy as $field => $direction) {
                $query->orderBy($field, $direction);
            }
        }
        if (!empty($limit)) {
            $query->limit($limit);
        }
        if (!empty($offset)) {
            $query->offset($offset);
        }
        return $query->get();
    }
}
