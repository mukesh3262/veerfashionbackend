<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    /** @use HasFactory<\Database\Factories\InquiryFactory> */
    use HasFactory;

    protected $table = 'inquiries';

    protected $fillable = [
        'name',
        'email',
        'isd_code',
        'mobile',
        'message',
    ];
}
