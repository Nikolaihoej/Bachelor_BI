<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    use HasFactory;

    protected $table = 'dashboards';

    protected $primaryKey = 'id';

    protected $fillable = [
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}