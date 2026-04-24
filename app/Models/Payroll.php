<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $primaryKey = 'payroll_id';
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id', 'employees_id');
    }

    public function additionalIncomes()
    {
        return $this->hasMany(AdditionalIncome::class, 'payroll_id', 'payroll_id');
    }

    public function savings()
    {
        return $this->hasMany(Saving::class, 'payroll_id', 'payroll_id');
    }
}
