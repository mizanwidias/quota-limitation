<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadReply extends Model
{
    use HasFactory;

    // pakai koneksi radius
    protected $connection = 'radius';

    // tabel asli
    protected $table = 'radreply';
    public $timestamps = false;

    // field yang boleh diisi mass-assignment
    protected $fillable = [
        'username',
        'attribute',
        'op',
        'value',
    ];
}
