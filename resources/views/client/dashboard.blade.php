@extends('layouts.client')

@section('title', 'Mi cuenta')

@section('client-content')
    <h1 class="h3 mb-4">Hola, {{ Auth::user()->name }}</h1>

    <div class="card">
        <div class="card-body">
            <p class="text-muted mb-0">
                Acá vas a ver tus sistemas, cuotas y pagos. Esta sección se completa en la fase del portal del cliente.
            </p>
        </div>
    </div>
@endsection
