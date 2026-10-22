<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dictionary extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dictionaries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'indonesia',
        'krama_alus',
        'krama_inggil',
        'ngoko',
    ];

    /**
     * Scope a query to search across all dictionary columns.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $keyword
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $keyword)
    {
        if (empty($keyword)) {
            return $query;
        }

        $term = trim($keyword);

        return $query->where(function ($q) use ($term) {
            $q->where('indonesia', 'like', "%{$term}%")
              ->orWhere('krama_alus', 'like', "%{$term}%")
              ->orWhere('krama_inggil', 'like', "%{$term}%")
              ->orWhere('ngoko', 'like', "%{$term}%");
        });
    }
}
