<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalIncome extends Model
{
    use HasFactory;

    protected $primaryKey = 'additional_income_id';
    protected $guarded = [];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payroll_id', 'payroll_id');
    }
}
