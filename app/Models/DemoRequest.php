<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DemoRequestStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'system_id', 'first_name', 'last_name', 'company_name', 'email', 'phone',
    'whatsapp', 'city', 'number_of_users', 'preferred_date', 'preferred_time',
    'comments', 'status', 'assigned_to', 'converted_customer_id',
])]
class DemoRequest extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => DemoRequestStatus::class,
            'number_of_users' => 'integer',
            'preferred_date' => 'date',
            'preferred_time' => 'string',
        ];
    }

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function convertedCustomer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'converted_customer_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(DemoRequestLog::class)->orderBy('created_at');
    }

    public function fullName(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }
}
