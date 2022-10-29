<?php

namespace App\Traits;

use Carbon\Carbon;

trait Searchable
{

    /*
    |--------------------------------------------------------------------------
    | Search Data
    |--------------------------------------------------------------------------
    |
    | This trait basically used in model. This will help to implement search.
    | It could search in multiple column, date to date etc.
    | But this trait unable to make search with multiple table.
    |
    */

    public function scopeSearchable($query, $columns, $like = true)
    {
        $search = request()->search;
        $search = $like ? "%$search%" : $search;
        foreach ($columns as $key => $column) {
            if ($key == 0) {
                $query->where($column, 'LIKE', $search);
            } else {
                $query->orWhere($column, 'LIKE', $search);
            }
        }
        return $query;
    }

    public function scopeFilter($query, $columns)
    {
        foreach ($columns as $columName) {
            $columns = array_keys(request()->all());
            if (in_array($columName, $columns) && request()->$columName != null) {
                $query->where($columName, request()->$columName);
            }
        }
        return $query;
    }

    function scopeDateFilter($query, $column = 'created_at')
    {
        if (!request()->date) {
            return $query;
        }
        $date      = explode('-', request()->date);

        $startDate = Carbon::parse(trim($date[0]))->format('Y-m-d');
        $endDate = Carbon::parse(trim(@$date[1]))->format('Y-m-d');

        request()->merge(['start_date' => $startDate, 'end_date' => $endDate]);

        request()->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date'   => 'nullable|date_format:Y-m-d',
        ]);

        return  $query->whereDate($column, '>=', $startDate)->whereDate($column, '<=', $endDate ?? $startDate);
    }
}