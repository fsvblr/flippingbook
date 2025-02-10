<?php

namespace Flippingbook\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;

    protected $table = 'flippingbook_categories';

    protected $fillable = [
        'title', 'description',
        'state',
    ];

    protected $casts = [
        'state' => 'boolean',
    ];

    /**
     * Get the publications for the category.
     */
    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class, 'category_id');
    }

    public static function getListCategories(): array
    {
        $categories = DB::table('flippingbook_categories')
            ->select('id', 'title')
            ->orderBy('title', 'ASC')
            ->get()
            ->toArray();

        return $categories;
    }
}
