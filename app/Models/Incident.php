<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $table = 'incidents';

    protected $fillable = [
        'report_month',
        'report_quarter',
        'incident_date',
        'found_date',
        'incident_description',
        'root_cause',
        'amount',
    ];

    protected $guarded = [];

    public function incidentDivisions()
    {
        return $this->hasMany(IncidentDivision::class, 'incidentId', 'id');
    }
}
