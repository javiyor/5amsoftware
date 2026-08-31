@if (session('demoSubmitted'))
    <div class="alert alert-success border-0" role="alert">
        <strong>¡Gracias!</strong> Tu solicitud fue recibida. Nos pondremos en contacto a la brevedad.
    </div>
@else
    <form method="POST" action="{{ route('demo.store') }}" class="text-start" novalidate>
        @csrf
        <input type="text" name="company_website" class="d-none" tabindex="-1" autocomplete="off" aria-hidden="true">

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="first_name">Nombre *</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}"
                       class="form-control @error('first_name') is-invalid @enderror" required>
                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="last_name">Apellido *</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}"
                       class="form-control @error('last_name') is-invalid @enderror" required>
                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="email">Email *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="company_name">Empresa</label>
                <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}"
                       class="form-control @error('company_name') is-invalid @enderror">
                @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="whatsapp">WhatsApp</label>
                <input type="tel" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}"
                       class="form-control @error('whatsapp') is-invalid @enderror">
                @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="city">Ciudad</label>
                <input type="text" id="city" name="city" value="{{ old('city') }}"
                       class="form-control @error('city') is-invalid @enderror">
                @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            @if (! empty($systems))
                <div class="col-12">
                    <label class="form-label" for="system_id">Sistema de interés</label>
                    <select id="system_id" name="system_id" class="form-select @error('system_id') is-invalid @enderror">
                        <option value="">Todavía no estoy seguro</option>
                        @foreach ($systems as $systemOption)
                            <option value="{{ $systemOption->id }}" @selected(old('system_id', $system->id ?? null) == $systemOption->id)>
                                {{ $systemOption->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('system_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            @endif
            <div class="col-md-6">
                <label class="form-label" for="preferred_date">Fecha preferida</label>
                <input type="date" id="preferred_date" name="preferred_date" value="{{ old('preferred_date') }}"
                       class="form-control @error('preferred_date') is-invalid @enderror">
                @error('preferred_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="preferred_time">Horario preferido</label>
                <input type="time" id="preferred_time" name="preferred_time" value="{{ old('preferred_time') }}"
                       class="form-control @error('preferred_time') is-invalid @enderror">
                @error('preferred_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label" for="comments">¿Contanos algo sobre tu proyecto?</label>
                <textarea id="comments" name="comments" rows="3" class="form-control @error('comments') is-invalid @enderror">{{ old('comments') }}</textarea>
                @error('comments')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <button type="submit" class="btn btn-brand w-100 mt-4">Solicitar demo gratuita</button>
    </form>
@endif
