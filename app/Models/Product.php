<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['name', 'price'];

    public function stocks(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'stocks')->withPivot('stock');
    }
}
