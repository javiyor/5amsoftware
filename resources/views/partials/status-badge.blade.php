@php
    $value = $status instanceof \BackedEnum ? $status->value : $status;
    $label = $status instanceof \BackedEnum ? $status->label() : $status;

    $map = [
        'active' => 'badge-soft-success',
        'approved' => 'badge-soft-success',
        'paid' => 'badge-soft-success',
        'completed' => 'badge-soft-success',
        'converted' => 'badge-soft-success',
        'lead' => 'badge-soft-info',
        'new' => 'badge-soft-info',
        'pending' => 'badge-soft-warning',
        'scheduled' => 'badge-soft-info',
        'contacted' => 'badge-soft-info',
        'interested' => 'badge-soft-primary',
        'demo' => 'badge-soft-primary',
        'overdue' => 'badge-soft-warning',
        'suspended' => 'badge-soft-danger',
        'rejected' => 'badge-soft-danger',
        'revoked' => 'badge-soft-danger',
        'cancelled' => 'badge-soft-secondary',
        'expired' => 'badge-soft-secondary',
        'waived' => 'badge-soft-secondary',
        'refunded' => 'badge-soft-secondary',
        'inactive' => 'badge-soft-secondary',
    ];
@endphp
<span class="badge {{ $map[$value] ?? 'badge-soft-secondary' }}">{{ $label }}</span>
