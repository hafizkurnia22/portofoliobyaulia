<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portfolio CV')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Animate CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    @if (!request()->is('admin/*'))
        @include('components.navbar')
    @endif

    <main>
        @yield('content')
    </main>

    @if (!request()->is('admin/*'))
        @php
            $contactName = trim($tentangSaya->nama ?? 'Hafiz');
            $contactRole = trim($tentangSaya->bidang ?? 'Web Developer');
            $availabilityText = trim($tentangSaya->status ?? 'Open for Collaboration');
            $whatsappNumber = preg_replace('/\D+/', '', $tentangSaya->whatsapp ?? '');

            if ($whatsappNumber !== '' && str_starts_with($whatsappNumber, '0')) {
                $whatsappNumber = '62' . substr($whatsappNumber, 1);
            }

            $whatsappNumber = $whatsappNumber !== '' ? $whatsappNumber : '6289697960980';
            $contactIntents = [
                [
                    'icon' => 'bi-briefcase-fill',
                    'label' => 'Rekrutmen',
                    'desc' => 'Diskusi peluang kerja, magang, atau interview.',
                    'message' => "Halo {$contactName}, saya tertarik membahas peluang rekrutmen setelah melihat portfolio Anda.",
                ],
                [
                    'icon' => 'bi-stars',
                    'label' => 'Kerja Sama Project',
                    'desc' => 'Bahas website, dashboard, atau sistem digital.',
                    'message' => "Halo {$contactName}, saya ingin berdiskusi tentang kerja sama project website atau sistem digital.",
                ],
                [
                    'icon' => 'bi-kanban-fill',
                    'label' => 'Tanya Project',
                    'desc' => 'Tanyakan detail fitur, teknologi, dan demo project.',
                    'message' => "Halo {$contactName}, saya ingin bertanya tentang project yang ada di portfolio Anda.",
                ],
                [
                    'icon' => 'bi-file-earmark-person-fill',
                    'label' => 'Minta CV',
                    'desc' => 'Minta CV terbaru atau profil profesional lengkap.',
                    'message' => "Halo {$contactName}, saya ingin meminta CV terbaru dan informasi profil profesional Anda.",
                ],
            ];
        @endphp

        @include('components.footer')

        <button type="button" class="floating-whatsapp" data-bs-toggle="modal" data-bs-target="#smartContactModal"
            aria-label="Buka pilihan kontak WhatsApp">
            <i class="bi bi-whatsapp"></i>
        </button>

        <div class="modal fade smart-contact-modal" id="smartContactModal" tabindex="-1"
            aria-labelledby="smartContactModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="smart-contact-shell">
                        <button type="button" class="btn-close smart-contact-close" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>

                        <div class="smart-contact-header">
                            <span class="smart-contact-kicker">
                                <span class="availability-live-dot"></span>
                                {{ $availabilityText !== '' ? $availabilityText : 'Open for Collaboration' }}
                            </span>

                            <h2 id="smartContactModalLabel">Pilih tujuan kontak</h2>
                            <p>
                                Pesan WhatsApp akan otomatis disesuaikan agar percakapan langsung tepat sasaran.
                            </p>
                        </div>

                        <div class="smart-contact-profile">
                            <div class="smart-contact-avatar">
                                <i class="bi bi-person-workspace"></i>
                            </div>
                            <div>
                                <strong>{{ $contactName }}</strong>
                                <span>{{ $contactRole }}</span>
                            </div>
                        </div>

                        <div class="smart-contact-grid">
                            @foreach ($contactIntents as $intent)
                                <a class="smart-contact-option"
                                    href="https://wa.me/{{ $whatsappNumber }}?text={{ rawurlencode($intent['message']) }}"
                                    target="_blank" rel="noopener">
                                    <span class="smart-contact-icon">
                                        <i class="bi {{ $intent['icon'] }}"></i>
                                    </span>

                                    <span>
                                        <strong>{{ $intent['label'] }}</strong>
                                        <small>{{ $intent['desc'] }}</small>
                                    </span>

                                    <i class="bi bi-arrow-up-right smart-contact-arrow"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 80,
            easing: 'ease-in-out',
        });
    </script>

    <!-- Admin JS -->
    <script src="{{ asset('js/admin-dashboard.js') }}"></script>

</body>

</html>
