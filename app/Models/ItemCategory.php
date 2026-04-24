<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';
    protected $guarded = [];

    public function spareparts()
    {
        return $this->hasMany(Sparepart::class, 'item_category_id', 'category_id');
    }
}
