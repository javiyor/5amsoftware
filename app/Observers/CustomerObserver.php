<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Customer;
use App\Services\Support\CodeGenerator;

class CustomerObserver
{
    public function creating(Customer $customer): void
    {
        if ($customer->code === null) {
            $customer->code = CodeGenerator::next('CLI-', Customer::query()->max('id'));
        }
    }
}
