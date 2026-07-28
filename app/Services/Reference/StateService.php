<?php

namespace App\Services\Reference;

use App\Models\Reference\State;
use App\Services\BaseService;

class StateService extends BaseService
{
    public function __construct(State $model)
    {
        parent::__construct($model);
    }
}
