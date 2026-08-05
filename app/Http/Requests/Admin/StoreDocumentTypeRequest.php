<?php

namespace App\Http\Requests\Admin;

use App\Enums\DocumentCategory;
use App\Enums\DocumentWorkflowStage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreDocumentTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('document-types.create');
    }
    
    protected function prepareForValidation(): void
{
    $this->merge([
        'required' => $this->boolean('required'),
        'allow_multiple_versions' => $this->boolean('allow_multiple_versions'),
        'is_active' => $this->boolean('is_active'),
    ]);
}

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],

            'code' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                'unique:document_types,code',
            ],

            'category' => [
                'required',
                new Enum(DocumentCategory::class),
            ],

            'workflow_stage' => [
                'required',
                new Enum(DocumentWorkflowStage::class),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'required' => [
    'boolean',
],

'allow_multiple_versions' => [
    'boolean',
],

            'display_order' => [
                'required',
                'integer',
                'min:0',
            ],

            'is_active' => [
    'boolean',
],
        ];
    }
}
