<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Payment;
use App\Services\Support\CodeGenerator;

class PaymentObserver
{
    public function creating(Payment $payment): void
    {
        if ($payment->payment_number === null) {
            $payment->payment_number = CodeGenerator::next('PAG-', Payment::query()->max('id'));
        }
    }
}
