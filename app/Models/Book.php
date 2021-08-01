<?php

namespace App\Models;

use App\Classes\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use Filterable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'books';

    public static $statuses = [
        0 => 'Unknown',
        1 => 'Pending',
        2 => 'Published',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'author_id',
        'status',
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
        return $query
             ->join('authors', 'books.author_id', '=', 'authors.id')
             ->with(['attachments','author'])
             ->withCount(['attachments']);

    }

    /**
     * Load all new books .
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status',0);
    }

    /**
     * Get all attachments that belongsTo this book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->HasMany(Attachment::class);
    }

    /**
     * Get the author that own this book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Accessors get status by text
     *
     * @return string
     */
    public function getStatusTextAttribute()
    {
        return self::$statuses[$this->status ? : 1];
    }

    /**
     * Accessors get status by text
     *
     * @param $value
     * @return string
     */
    public function setStatusAttribute($value)
    {
        if (is_numeric($value) && in_array($value, self::$statuses)) {
            $this->attributes['status'] = $value;
        } elseif (false !== $key = array_search($value, self::$statuses)) {
            $this->attributes['status'] = $key;
        } else {
            $this->attributes['status'] = 1;
        }
    }
}
