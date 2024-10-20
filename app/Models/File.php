<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $fillable = [
        'file_description',
        'file',
        'incidentId'
    ];

    protected $guarded = [];

    public function event()
    {
        return $this->belongsTo(Incident::class, 'incidentId', 'id');
    }
}
