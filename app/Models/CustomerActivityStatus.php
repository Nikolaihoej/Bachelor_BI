<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerActivityStatus extends Model
{
    use HasFactory;

    protected $table = 'customer_activity_status'; // Tabellenavn
    protected $primaryKey = 'ActivityStatusID'; // Primær nøgle

    protected $fillable = [
        'HasTrainedLastMonth',
        'DaysSinceLastVisit',
        'TrainingSessionsThisMonth',
    ];
}
