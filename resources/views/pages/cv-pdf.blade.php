<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>CV {{ $tentangSaya->nama ?? '' }}</title>

    @php
        $options = $cvOptions ?? [];
        $colors = $options['colors'] ?? [
            'primary' => '#0b1f3a',
            'secondary' => '#2563eb',
            'soft' => '#dbeafe',
            'muted' => '#bfdbfe',
        ];
        $template = $options['template'] ?? 'modern';
        $showPhoto = $options['show_photo'] ?? true;
        $showProfile = $options['show_profile'] ?? true;
        $showSkills = $options['show_skills'] ?? true;
        $showExperience = $options['show_experience'] ?? true;
        $showCertifications = $options['show_certifications'] ?? true;
    @endphp

    <style>
        @page {
            margin: {{ $template === 'compact' ? '18px' : '26px' }};
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1e293b;
            font-size: {{ $template === 'compact' ? '10px' : '11px' }};
            line-height: 1.5;
        }

        .header {
            background: {{ $colors['primary'] }};
            color: white;
            padding: {{ $template === 'compact' ? '14px 18px' : '18px 22px' }};
            border-radius: 14px;
            margin-bottom: 14px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .photo-cell {
            width: 105px;
            vertical-align: middle;
        }

        .photo {
            width: 82px;
            height: 82px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
        }

        .info-cell {
            vertical-align: middle;
        }

        .name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .job {
            font-size: 14px;
            color: #bfdbfe;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .status {
            display: inline-block;
            background: white;
            color: {{ $colors['primary'] }};
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
        }

        .contact-box {
            background: {{ $colors['soft'] }};
            border-left: 5px solid {{ $colors['primary'] }};
            padding: 11px 14px;
            margin-bottom: 15px;
            border-radius: 10px;
        }

        .contact-row {
            margin-bottom: 4px;
        }

        .contact-row span {
            display: inline-block;
            width: 48%;
            margin-bottom: 4px;
        }

        .section {
            margin-top: 14px;
            margin-bottom: 14px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: {{ $colors['primary'] }};
            border-bottom: 2px solid {{ $colors['soft'] }};
            padding-bottom: 5px;
            margin-bottom: 9px;
            margin-top: 12px;
            page-break-after: avoid;
        }

        .profile {
            text-align: justify;
            color: #475569;
            line-height: 1.7;
        }

        .skill-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px;
        }

        .skill-cell {
            width: 50%;
            background: {{ $colors['primary'] }};
            color: #ffffff;
            padding: 9px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }

        .skill-name {
            width: 75%;
        }

        .skill-percent {
            width: 25%;
            text-align: right;
            color: {{ $colors['muted'] }};
        }

        .item {
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
            page-break-inside: avoid;
        }

        .item:last-child {
            border-bottom: none;
        }

        .item-title {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 3px;
        }

        .company {
            color: {{ $colors['secondary'] }};
        }

        .period {
            display: inline-block;
            background: {{ $colors['soft'] }};
            color: {{ $colors['primary'] }};
            padding: 3px 8px;
            border-radius: 14px;
            font-size: 9.5px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .desc {
            color: #475569;
            text-align: justify;
        }

        .footer-note {
            margin-top: 16px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
        }
    </style>
</head>

<body>

    <div class="header">
        <table class="header-table">
            <tr>
                <td class="photo-cell">
                    @php
                        $fotoPath = public_path('images/' . ($tentangSaya->foto ?? ''));
                    @endphp

                    @if ($showPhoto && !empty($tentangSaya->foto) && file_exists($fotoPath))
                        <img class="photo" src="data:image/png;base64,{{ base64_encode(file_get_contents($fotoPath)) }}">
                    @endif
                </td>

                <td class="info-cell">
                    <div class="name">{{ $tentangSaya->nama ?? 'Nama Anda' }}</div>
                    <div class="job">{{ $tentangSaya->bidang ?? '' }}</div>

                    @if (!empty($tentangSaya->status))
                        <div class="status">{{ $tentangSaya->status }}</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="contact-box">
        <div class="contact-row">
            <span><strong>WhatsApp:</strong> {{ $tentangSaya->whatsapp ?? '-' }}</span>
            <span><strong>Email:</strong> {{ $tentangSaya->email_kontak ?? '-' }}</span>
        </div>

        <div class="contact-row">
            @if (!empty($tentangSaya->instagram))
                <span><strong>Instagram:</strong> tersedia</span>
            @endif

            @if (!empty($tentangSaya->facebook))
                <span><strong>Facebook:</strong> tersedia</span>
            @endif

            @if (!empty($tentangSaya->tiktok))
                <span><strong>TikTok:</strong> tersedia</span>
            @endif
        </div>
    </div>

    @if ($showProfile)
    <div class="section">
        <div class="section-title">PROFIL</div>

        <div class="profile">
            {{ $tentangSaya->deskripsi_1 ?? '' }}

            @if (!empty($tentangSaya->deskripsi_2))
                <br><br>
                {{ $tentangSaya->deskripsi_2 }}
            @endif
        </div>
    </div>
    @endif

    @if ($showSkills)
    <div class="section">
        <div class="section-title">SKILL</div>

        <table class="skill-table">
            @forelse ($skill->chunk(2) as $chunk)
                <tr>
                    @foreach ($chunk as $item)
                        <td class="skill-cell">
                            <table width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="skill-name">
                                        {{ $item->nama_skill }}
                                    </td>
                                    <td class="skill-percent">
                                        {{ $item->persentase }}%
                                    </td>
                                </tr>
                            </table>
                        </td>
                    @endforeach

                    @if ($chunk->count() === 1)
                        <td width="50%"></td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td>Belum ada skill.</td>
                </tr>
            @endforelse
        </table>
    </div>
    @endif

    @if ($showExperience)
    <div class="section">
        <div class="section-title">PENGALAMAN KERJA</div>

        @forelse ($pengalaman as $item)
            <div class="item">
                <div class="item-title">
                    {{ $item->jabatan }}
                    <span class="company">- {{ $item->nama_perusahaan }}</span>
                </div>

                <div class="period">
                    {{ $item->periode }}
                </div>

                <div class="desc">
                    {{ $item->deskripsi }}
                </div>
            </div>
        @empty
            <p>Belum ada pengalaman kerja.</p>
        @endforelse
    </div>
    @endif

    @if ($showCertifications)
    <div class="section">
        <div class="section-title">SERTIFIKASI</div>

        @forelse ($sertifikasi as $item)
            <div class="item">
                <div class="item-title">
                    {{ $item->nama_sertifikat }}
                </div>

                <div class="period">
                    {{ $item->penyelenggara }} - {{ $item->tahun }}
                </div>

                @if (!empty($item->deskripsi))
                    <div class="desc">
                        {{ $item->deskripsi }}
                    </div>
                @endif
            </div>
        @empty
            <p>Belum ada sertifikasi.</p>
        @endforelse
    </div>
    @endif

    <div class="footer-note">
        Curriculum Vitae dibuat otomatis dari website portfolio.
    </div>

</body>

</html>
