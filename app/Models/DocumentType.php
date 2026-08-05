<?php

namespace App\Models;

use App\Enums\DocumentCategory;
use App\Enums\DocumentWorkflowStage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Candidate\CandidateDocument;



class DocumentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'category',
        'workflow_stage',
        'description',
        'required',
        'allow_multiple_versions',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'category' => DocumentCategory::class,
            'workflow_stage' => DocumentWorkflowStage::class,
            'required' => 'boolean',
            'allow_multiple_versions' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function formTemplates(): HasMany
    {
        return $this->hasMany(FormTemplate::class);
    }

    public function activeTemplate(): HasOne
    {
        return $this->hasOne(FormTemplate::class)
            ->where('is_active', true)
            ->latestOfMany('effective_date');
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    public function candidateDocuments(): HasMany
{
    return $this->hasMany(CandidateDocument::class);
}

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function isOptional(): bool
    {
        return ! $this->required;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function allowsMultipleVersions(): bool
    {
        return $this->allow_multiple_versions;
    }

    public function hasActiveTemplate(): bool
    {
        return $this->activeTemplate()->exists();
    }


}
