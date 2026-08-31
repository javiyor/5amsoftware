<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\CustomerSystem;
use App\Services\Support\CodeGenerator;

class CustomerSystemObserver
{
    public function creating(CustomerSystem $customerSystem): void
    {
        if ($customerSystem->contract_number === null) {
            $customerSystem->contract_number = CodeGenerator::next('CTR-', CustomerSystem::query()->max('id'));
        }
    }
}
