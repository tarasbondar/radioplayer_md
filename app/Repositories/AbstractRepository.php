<?php declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    public function save(Model $entity): bool
    {
        return $entity->save();
    }
}
