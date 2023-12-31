<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class File extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $fillable = [
        'name',
        'description',
        'thumbnail',
        'path',
        'price',
        'dates',
        'slug',
        'payment_status',
    ];

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }
}
