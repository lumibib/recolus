<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'screen' => 'array',
        'window' => 'array',
    ];

    /**
     * Get the site that owns the record.
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Scope a query to only include the ones created between a period.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $period
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePeriod($query, $period = 'today')
    {
        return $query->whereBetween('created_at', [
            data_get(config('recolus.periods.'.$period), 'start'),
            data_get(config('recolus.periods.'.$period), 'end')
        ]);
    }

    /**
     * Scope a query to only include the ones having a variable.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $period
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVariable($query, $variable = null)
    {
        return $query->when($variable, function ($query, $variable) {
            $query->where('custom_variable', $variable);
        });
    }
}
