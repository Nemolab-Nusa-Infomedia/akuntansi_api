<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCompany extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = [];
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function company(): BelongsTo {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
