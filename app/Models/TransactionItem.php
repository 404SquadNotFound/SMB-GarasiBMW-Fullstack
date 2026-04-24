<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'item_id';
    protected $guarded = [];

    public function transaction()
    {
        return $this->belongsTo(ServiceTransaction::class, 'transaction_id', 'transaction_id');
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'spare_part_id', 'sparepart_id');
    }
}
