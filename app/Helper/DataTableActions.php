<?php

namespace App\Helper;

use Illuminate\Database\Eloquent\Builder;

trait DataTableActions
{
    protected function applyFilters(Builder $query, array $filters = [])
    {
        // Decode JSON filters from query string
        $filters = json_decode(request()->get('filters', '[]'), true);

        foreach ($filters as $filter) {
            $column = $filter['key'] ?? null;
            $value = $filter['value'] ?? null;
            $operator = $filter['operator'] ?? 'contains';

            if (!$column || is_null($value)) {
                continue;
            }

            switch ($operator) {
                case 'equals':
                    $query->where($column, '=', $value);
                    break;
                case 'contains':
                    $query->where($column, 'like', "%{$value}%");
                    break;
                case 'gt':
                    $query->where($column, '>', $value);
                    break;
                case 'lt':
                    $query->where($column, '<', $value);
                    break;
                case 'gte':
                    $query->where($column, '>=', $value);
                    break;
                case 'lte':
                    $query->where($column, '<=', $value);
                    break;
                default:
                    $query->where($column, 'like', "%{$value}%");
            }
        }

        return $query;
    }

    protected function addSearch(Builder $query)
    {
        if ($search = request()->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")->orWhere('category', 'like', "%{$search}%");
            });
        }

        return $query;
    }

   protected function addSorting(Builder $query, string $defaultSort = 'id')
{
    $sortInput = request()->input('sort');
    $sorts = $sortInput ? json_decode($sortInput, true) : null;

    if (is_array($sorts) && count($sorts) > 0) {
        foreach ($sorts as $sort) {
            $column = $sort['key'] ?? null;
            $direction = strtolower($sort['direction'] ?? 'asc');

            if ($column && in_array($direction, ['asc', 'desc'])) {
                $query->orderBy($column, $direction);
            }
        }
    } else {
        // Default sort by id descending if no sort provided
        $query->orderBy($defaultSort, 'desc');
    }

    return $query;
}


    protected function getProcessedData(Builder $query, $perPage = 10)
    {
        $query = $this->applyFilters($query);
        $query = $this->addSearch($query);
        $query = $this->addSorting($query);

        return $query->paginate($perPage);
    }
}
