<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactTable extends Model
{
    use HasFactory;

    protected $table = 'fact_table'; // Matcher navnet på din tabel

    protected $fillable = [
        'CustomerID',
        'MembershipTypeID',
        'ActivityStatusID',
    ];
    //First is foreign key in fact_table, second is primay key in customer table
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID'); 
    }

    public function activityStatus()
    {
        return $this->belongsTo(CustomerActivityStatus::class, 'ActivityStatusID', 'ActivityStatusID');
    }

    public function membershipType()
    {
        return $this->belongsTo(MembershipType::class, 'MembershipTypeID', 'MembershipTypeID');
    }
}
