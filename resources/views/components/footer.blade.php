<footer class="site-footer" aria-label="Informasi dan navigasi situs">
    <div class="container">
        <div class="site-footer-main">

            <div class="site-footer-intro">
                <a class="site-footer-logo" href="{{ url('/') }}" aria-label="Hafiz’s Portofolio — Beranda">
                    <svg viewBox="525 260 1030 228" aria-hidden="true" focusable="false"><image href="{{ asset('images/logo-mhk-premium.png') }}" width="2073" height="758" /></svg>
                </a>
                <p>Kenali pengalaman, karya, dan keahlian saya. Mari terhubung untuk peluang dan kerja sama yang bermanfaat.</p>
            </div>
            <nav class="site-footer-links" aria-label="Jelajahi profil">
                <h2>Jelajahi profil</h2>
                <a href="{{ url('/#tentang') }}">Tentang saya</a>
                <a href="{{ url('/#pengalaman') }}">Pengalaman</a>
                <a href="{{ url('/#my-project') }}">Project</a>
                <a href="{{ url('/#skill') }}">Keahlian</a>
                <a href="{{ url('/#sertifikasi') }}">Sertifikasi</a>
            </nav>
            <nav class="site-footer-links" aria-label="Belajar dan karier">
                <h2>Belajar & karier</h2>
                <a href="{{ route('tryout.index', ['tab' => 'materi']) }}">Materi CPNS</a>
                <a href="{{ route('tryout.index', ['tab' => 'simulasi']) }}">Simulasi ujian</a>
                <a href="{{ route('cv.builder') }}">Buat CV saya <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
            </nav>
            <div class="site-footer-links">
                <h2>Mari terhubung</h2>
                <p>Untuk pertanyaan, rekrutmen, atau diskusi project.</p>
                <button type="button" class="site-footer-contact-button" data-bs-toggle="modal" data-bs-target="#smartContactModal"><i class="bi bi-whatsapp" aria-hidden="true"></i> Hubungi saya</button>
                @if (!empty($tentangSaya->email_kontak))
                    <a class="site-footer-email" href="mailto:{{ $tentangSaya->email_kontak }}">{{ $tentangSaya->email_kontak }}</a>
                @endif
            <div class="site-footer-social">

                @if (!empty($tentangSaya->facebook))
                    <a href="{{ $tentangSaya->facebook }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook (tab baru)">
                        <i class="bi bi-facebook"></i>
                    </a>
                @endif

                @if (!empty($tentangSaya->instagram))
                    <a href="{{ $tentangSaya->instagram }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram (tab baru)">
                        <i class="bi bi-instagram"></i>
                    </a>
                @endif

                @if (!empty($tentangSaya->tiktok))
                    <a href="{{ $tentangSaya->tiktok }}" target="_blank" rel="noopener noreferrer" aria-label="TikTok (tab baru)">
                        <i class="bi bi-tiktok"></i>
                    </a>
                @endif

            </div>

        </div>
        </div>
        <div class="site-footer-bottom">
            <span>© {{ date('Y') }} {{ $contactName ?: 'Hafiz' }}. Hak cipta dilindungi.</span>
            <a href="#page-top">Kembali ke atas <i class="bi bi-arrow-up" aria-hidden="true"></i></a>
        </div>
    </div>
</footer>
