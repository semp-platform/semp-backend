<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Party\PoliticalParty;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Candidate\CandidateDocument;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function politicalParties(): BelongsToMany
    {
        return $this->belongsToMany(
            PoliticalParty::class,
            'political_party_users'
        )
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function uploadedCandidateDocuments()
    {
        return $this->hasMany(
            CandidateDocument::class,
            'uploaded_by'
        );
    }

    public function verifiedCandidateDocuments()
    {
        return $this->hasMany(
            CandidateDocument::class,
            'verified_by'
        );
    }
}
