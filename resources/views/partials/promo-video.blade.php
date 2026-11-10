@php
    $promoVideoPath = $videoPath ?? '';
    $promoVideoUrl = $videoUrl ?? '';
    $promoVideoHost = $promoVideoUrl ? strtolower((string) parse_url($promoVideoUrl, PHP_URL_HOST)) : '';
    $promoVideoUrlPath = $promoVideoUrl ? (string) parse_url($promoVideoUrl, PHP_URL_PATH) : '';
    $promoVideoExtension = strtolower(pathinfo($promoVideoPath, PATHINFO_EXTENSION));
    $promoVideoMimeType = match ($promoVideoExtension) {
        'webm' => 'video/webm',
        'mov' => 'video/quicktime',
        default => 'video/mp4',
    };
    $youtubeId = null;
    $vimeoId = null;

    if (in_array($promoVideoHost, ['youtube.com', 'www.youtube.com', 'm.youtube.com'], true)) {
        parse_str((string) parse_url($promoVideoUrl, PHP_URL_QUERY), $youtubeQuery);
        $youtubeId = $youtubeQuery['v'] ?? null;
    } elseif (in_array($promoVideoHost, ['youtu.be', 'www.youtu.be'], true)) {
        $youtubeId = trim($promoVideoUrlPath, '/');
    }

    if ($promoVideoHost === 'vimeo.com' || $promoVideoHost === 'www.vimeo.com') {
        $vimeoId = trim($promoVideoUrlPath, '/');
    } elseif ($promoVideoHost === 'player.vimeo.com' && preg_match('~^/video/(\d+)~', $promoVideoUrlPath, $match)) {
        $vimeoId = $match[1];
    }

    $youtubeId = is_string($youtubeId) && preg_match('/^[A-Za-z0-9_-]{11}$/', $youtubeId) ? $youtubeId : null;
    $vimeoId = is_string($vimeoId) && preg_match('/^\d+$/', $vimeoId) ? $vimeoId : null;
    $directVideoUrl = $promoVideoUrl && preg_match('/\.(mp4|webm|mov)$/i', $promoVideoUrlPath);
@endphp

@if ($promoVideoPath)
    <video class="w-100 rounded-4" controls preload="metadata" playsinline>
        <source src="{{ asset('storage/' . $promoVideoPath) }}" type="{{ $promoVideoMimeType }}">
        Tu navegador no puede reproducir este video.
    </video>
@elseif ($youtubeId)
    <div class="ratio ratio-16x9 overflow-hidden rounded-4">
        <iframe src="https://www.youtube-nocookie.com/embed/{{ $youtubeId }}" title="{{ $title ?? 'Video promocional' }}" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>
@elseif ($vimeoId)
    <div class="ratio ratio-16x9 overflow-hidden rounded-4">
        <iframe src="https://player.vimeo.com/video/{{ $vimeoId }}" title="{{ $title ?? 'Video promocional' }}" loading="lazy" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
    </div>
@elseif ($directVideoUrl)
    <video class="w-100 rounded-4" controls preload="metadata" playsinline>
        <source src="{{ $promoVideoUrl }}">
        Tu navegador no puede reproducir este video.
    </video>
@elseif ($promoVideoUrl)
    <a class="btn btn-outline-dark" href="{{ $promoVideoUrl }}" target="_blank" rel="noopener noreferrer">
        Ver video promocional <span aria-hidden="true">↗</span>
    </a>
@endif
