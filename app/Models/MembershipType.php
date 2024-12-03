<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipType extends Model
{
    use HasFactory;

    protected $table = 'membership_types'; // Tabellenavn
    protected $primaryKey = 'MembershipTypeID'; // Primær nøgle

    protected $fillable = [
        'TypeName',
    ];
}
