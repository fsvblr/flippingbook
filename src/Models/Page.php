<?php

namespace Flippingbook\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Page extends Model
{
    use HasFactory;

    protected $table = 'flippingbook_pages';

    protected $fillable = [
        'publication_id',
        'title', 'ordering', 'image',
    ];

    protected $touches = ['publication'];

    /**
     * Get the publication that owns the page.
     */
    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class);
    }

    /**
     * Returns the maximum ordering in publications in the form:
     * key - publication ID, value - maximum ordering.
     *
     * @return string
     */
    public static function getMaxOrderings(): string
    {
        $maxOrderings = DB::table('flippingbook_pages')
            ->select('publication_id', DB::raw('MAX(ordering) as max_ordering'))
            ->groupBy('publication_id')
            ->get()
            ->pluck('max_ordering', 'publication_id')
            ->toJson()
        ;

        return $maxOrderings ?? json_encode([]);
    }
}
