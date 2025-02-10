<?php

namespace Flippingbook\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Publication extends Model
{
    use HasFactory;

    protected $table = 'flippingbook_publications';

    protected $fillable = [
        'category_id',
        'title',
        'state', 'preview',
        'direction', 'show_slider',
        'author', 'show_author_category', 'show_author_publication',
        'description', 'show_description_category', 'show_description_publication',
    ];

    protected $casts = [
        'state' => 'boolean',
        'show_slider' => 'boolean',
        'show_author_category' => 'boolean',
        'show_author_publication' => 'boolean',
        'show_description_category' => 'boolean',
        'show_description_publication' => 'boolean',
    ];

    /**
     * Get the pages for the publication.
     */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'publication_id');
    }

    /**
     * Get the category that owns the publication.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public static function getListPublications(): array
    {
        $publications = DB::table('flippingbook_publications')
            ->select('id', 'title')
            ->orderBy('title', 'ASC')
            ->get()
            ->toArray();

        return $publications;
    }
}
