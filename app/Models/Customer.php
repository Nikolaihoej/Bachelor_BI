<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers'; // Tabellenavn
    protected $primaryKey = 'CustomerID'; // Primær nøgle

    protected $fillable = [
        'Name',
        'Address',
        'Age',
    ];
}
