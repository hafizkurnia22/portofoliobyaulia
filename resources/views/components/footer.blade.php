@php
    $socialLinks = [
        'Facebook' => $tentangSaya->facebook ?? null,
        'Instagram' => $tentangSaya->instagram ?? null,
        'TikTok' => $tentangSaya->tiktok ?? null,
    ];
@endphp

<footer class="portfolio-footer" aria-labelledby="footer-title">
    <div class="container">
        <div class="portfolio-footer-main">
            <h2 id="footer-title">Mari terhubung<span>.</span></h2>

            <div class="portfolio-footer-contact">
                @if (!empty($tentangSaya->email_kontak))
                    <a class="portfolio-footer-email" href="mailto:{{ $tentangSaya->email_kontak }}">
                        {{ $tentangSaya->email_kontak }}
                    </a>
                @endif
                <button class="portfolio-footer-whatsapp" type="button" data-bs-toggle="modal" data-bs-target="#smartContactModal">
                    WhatsApp <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <div class="portfolio-footer-bottom">
            <small>© {{ date('Y') }} {{ $contactName ?: 'Hafiz' }}</small>

            <div class="portfolio-footer-links">
                @foreach ($socialLinks as $label => $url)
                    @if (!empty($url))
                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer">{{ $label }}</a>
                    @endif
                @endforeach
                <a href="#page-top">Kembali ke atas <i class="bi bi-arrow-up" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
</footer>
