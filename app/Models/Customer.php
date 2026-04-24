<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';
    protected $primaryKey = 'customer_id';

    // Pake fillable biar aman dari mass assignment
    protected $fillable = [
        'name',
        'phone_number',
        'address',
        'created_by',
        'edited_by'
    ];

    /**
     * Relasi ke pembuat data (Employee)
     */
    public function creator(): BelongsTo
    {
        // created_by (FK di tabel customers) nyambung ke employees_id (PK di tabel employees)
        return $this->belongsTo(Employee::class, 'created_by', 'employees_id');
    }

    /**
     * Relasi ke unit mobil pelanggan
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'customer_id', 'customer_id');
    }
}
