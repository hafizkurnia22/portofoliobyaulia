@extends('layouts.app')

@section('title', 'Detail Riwayat Tryout')

@section('content')
    <section class="admin-dashboard">
        <div class="container">
            <div class="admin-header" data-aos="fade-down">
                <div>
                    <span class="admin-label">Riwayat Tryout</span>
                    <h1>{{ $peserta->nama }}</h1>
                    <p>{{ $peserta->username }} · {{ $riwayat->total() }} riwayat tryout</p>
                </div>
                <div class="admin-header-right">
                    <a href="{{ url('/admin/dashboard?active_tab=riwayat-tryout') }}" class="btn-admin-logout btn-admin-back">
                        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
                    </a>
                </div>
            </div>

            <div class="admin-table-card" data-aos="fade-up">
                <div class="admin-table-header">
                    <div>
                        <h5>History Tryout</h5>
                        <p>Rincian semua tryout yang telah dikerjakan oleh peserta.</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table admin-table align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Skor</th>
                                <th>Dijawab</th>
                                <th>Benar</th>
                                <th>Ragu</th>
                                <th>Durasi</th>
                                <th>Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $item)
                                <tr>
                                    <td>{{ $riwayat->firstItem() + $loop->index }}</td>
                                    <td><span class="admin-badge">{{ $item->total_skor }}</span></td>
                                    <td>{{ $item->total_dijawab }}/{{ $item->total_soal }}</td>
                                    <td>{{ $item->total_benar }}</td>
                                    <td>{{ $item->total_ragu }}</td>
                                    <td>{{ gmdate('H:i:s', $item->durasi_detik) }}</td>
                                    <td>{{ $item->finished_at?->format('d M Y H:i') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada riwayat tryout</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $riwayat->links() }}</div>
            </div>
        </div>
    </section>
@endsection
