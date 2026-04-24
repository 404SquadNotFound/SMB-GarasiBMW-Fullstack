<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
    use HasFactory;

    protected $primaryKey = 'savings_id';
    protected $guarded = [];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payroll_id', 'payroll_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id', 'employees_id');
    }
}
