<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use function array_filter;
use function array_reverse;
use function collect;
use function explode;
use function implode;
use function method_exists;
use function request;
use function strrpos;
use function strtolower;
use function substr;
use function trim;

trait Searchable
{
    use ResolvesRelations;

    /**
     * Apply searching to query
     *
     * @param \Illuminate\Http\Request|null $request
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\Relation|null $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applySearching(Request $request = null, Builder|Model|Relation $query = null): Builder
    {
        /** @var \Illuminate\Http\Request $request */
        $request ??= request();

        $query ??= $this->query;
        if ($query instanceof Model) {
            $query = $query->newQuery();
        } else if ($query instanceof Relation) {
            $query = $query->getQuery();
        }

        $columns = array_filter(Arr::wrap($request->input('key')));
        $search = trim(strtolower($request->input('search')));

        if (!empty($columns) && !empty($search)) {
            $query = $this->searchColumn($query, $columns, trim(strtolower($search)));
        }
        if ($request->has('active') && Schema::hasColumn($query->getQuery()->from, 'active')) {
            $query = $query->where('active', $request->input('active'));
        }
        return $query;
    }

    /**
     * Search by any column
     *
     * @param mixed $query
     * @param string[] $columns
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function searchColumn(Builder $query, array $columns, string $search): Builder
    {
        /**
         * to get the results on based revers words sentence as well.
         */
        $explodedSearch = explode(" ", $search);
        $reverseArray = array_reverse($explodedSearch);
        $newSearchString = implode(" ", $reverseArray);

        $searchArray = [
            $search,
            $newSearchString
        ];

        $model = $query->getModel();

        return $query->where(
            fn(Builder $query) => collect($columns)
                ->reduce(function (Builder $query, string $column) use ($model, $searchArray) {
                    if (($pos = strrpos($column, '.')) !== false) {
                        $relation = substr($column, 0, $pos);

                        if ($this->checkRelation($model, $relation)) {
                            $column = substr($column, $pos + 1);

                            return $query->orWhereHas(
                                $relation,
                                fn(Builder $query) => $query->where(
                                    fn(Builder $query) => $this->performSearch($query, $column, $searchArray)
                                )
                            );
                        }

                        return $query;
                    }

                    return $query->orWhere(fn(Builder $query) => $this->performSearch($query, $column, $searchArray));
                }, $query)
        );
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $column
     * @param array $searchValues
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function performSearch(Builder $query, string $column, array $searchValues): Builder
    {
        $model = $query->getModel();
        $isTranslatable = method_exists($model, 'isTranslatableAttribute') &&
            method_exists($model, 'scopeOrWhereTranslation') &&
            $model->isTranslatableAttribute($column);

        $grammar = $query->toBase()->getGrammar();
        $qualifiedColumn = $grammar->wrap($query->qualifyColumn($column));

        foreach ($searchValues as $search) {
            if ($isTranslatable) {
                $query = $query->orWhereTranslation($column, 'LIKE', "%{$search}%");

                continue;
            }

            $query = $query->orWhereRaw("LOWER({$qualifiedColumn}) LIKE ? ", ['%' . $search . '%']);
        }

        return $query;
    }
}
