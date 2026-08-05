<?php

namespace App\Policies;

use App\Models\DocumentType;
use App\Models\User;

class DocumentTypePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('document-types.view');
    }

    public function view(User $user, DocumentType $documentType): bool
    {
        return $user->can('document-types.view');
    }

    public function create(User $user): bool
    {
        return $user->can('document-types.create');
    }

    public function update(User $user, DocumentType $documentType): bool
    {
        return $user->can('document-types.update');
    }

    public function activate(User $user, DocumentType $documentType): bool
    {
        return $user->can('document-types.activate');
    }

    public function deactivate(User $user, DocumentType $documentType): bool
    {
        return $user->can('document-types.deactivate');
    }
}
