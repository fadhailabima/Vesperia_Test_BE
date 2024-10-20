<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentDivision extends Model
{
    use HasFactory;

    protected $table = 'incident_divisions';

    protected $fillable = [
        'incidentId',
        'divisionId',
    ];

    protected $guarded = [];

    public function incident()
    {
        return $this->belongsTo(Incident::class, 'incidentId', 'id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'divisionId', 'id');
    }
}
