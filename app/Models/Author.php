<?php

namespace App\Models;

use App\Classes\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use Filterable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'authors';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Load all required relationships with only necessary content.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLoadRelations($query)
    {
        return $query->with(['books'])
            ->withCount(['books']);
    }

    /**
     * Get all books that belongsTo this author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function books()
    {
        return $this->HasMany(Book::class);
    }

    /**
     * Accessors get fullName text
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return  $this->attributes['first_name'].' '.$this->attributes['last_name'];
    }

}
