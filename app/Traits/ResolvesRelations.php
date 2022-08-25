<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use function array_shift;
use function explode;
use function method_exists;
use function trim;

trait ResolvesRelations
{

    private function checkRelation(Model $model, string $relation): bool
    {
        if (empty($relation)) {
            return false;
        }

        $path = explode('.', $relation);
        while (!empty($path)) {
            $segment = trim(array_shift($path));
            if (
                empty($segment) ||
                !method_exists($model, $segment)
            ) {
                return false;
            }

            $relation = $model->{$segment}();
            if (!($relation instanceof Relation)) {
                return false;
            }

            $model = $relation->getRelated();
        }

        return true;
    }
}
