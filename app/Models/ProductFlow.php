<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductFlow extends Model
{
    use HasFactory;

    public const ACTION_CREATE = 'create';
    public const ACTION_UPDATE = 'update';
    public const ACTION_CANCEL = 'cancel';
    public const ACTION_RENEW = 'renew';
    public const TYPE_ORDER = 'order';

    public $timestamps = false;
    protected $fillable = [
        'source_type',
        'source_id',
        'source_action',
        'warehouse_id',
        'product_id',
        'old_value',
        'new_value',
        'diff',
        'created_at',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
