<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reportcase extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'totalConfirmed',
        'totalDeaths',
        'totalActive',
        'dateInfo',
        'diseaseId',
        'localizationId'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        //
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'dateInfo' => 'datetime:Y-m-d',
        ];
    }

    /**
     * Get the disease associated with the report case.
     */
    public function disease()
    {
        return $this->belongsTo(Disease::class, 'diseaseId');
    }

    /**
     * Get the localization associated with the report case.
     */
    public function localization()
    {
        return $this->belongsTo(Localization::class, 'localizationId');
    }
}
