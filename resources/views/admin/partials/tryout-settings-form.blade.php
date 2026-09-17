<form action="/simpan-tryout-pengaturan" method="POST" enctype="multipart/form-data" class="tryout-setting-card mb-4">
    @csrf

    <div>
        <h6>Pengaturan Tryout</h6>
        <p>Pengaturan ini berlaku untuk peserta di halaman Tryout CPNS.</p>
    </div>

    <div class="mb-3">
        <label for="kisi_kisi_deskripsi" class="form-label fw-semibold">Keterangan Kisi-kisi</label>
        <textarea id="kisi_kisi_deskripsi" name="kisi_kisi_deskripsi" rows="4" class="form-control"
            placeholder="Contoh: Materi dan simulasi ujian ini disusun berdasarkan kisi-kisi SKD CPNS terbaru...">{{ old('kisi_kisi_deskripsi', $tryoutPengaturan->kisi_kisi_deskripsi) }}</textarea>
        <small class="text-muted">Teks ini tampil di beranda Tryout CPNS sebagai acuan peserta.</small>
    </div>

    <div class="mb-3">
        <label for="permenpan_file" class="form-label fw-semibold">Upload Surat PermenPAN</label>
        <input type="file" id="permenpan_file" name="permenpan_file" class="form-control" accept="application/pdf,.pdf">
        <small class="text-muted">
            Format PDF maksimal 5 MB.
            @if ($tryoutPengaturan->permenpan_file)
                File aktif:
                <a href="{{ asset('storage/' . $tryoutPengaturan->permenpan_file) }}" target="_blank" rel="noopener">
                    {{ $tryoutPengaturan->permenpan_nama ?: 'Surat PermenPAN' }}
                </a>
            @endif
        </small>
    </div>

    <div class="tryout-setting-fields">
        <div class="tryout-duration-control">
            <label for="durasi_menit">Durasi Ujian</label>
            <div>
                <input type="number" id="durasi_menit" name="durasi_menit" class="form-control"
                    min="1" max="300" value="{{ old('durasi_menit', $tryoutPengaturan->durasi_menit ?? 45) }}" required>
                <span>menit</span>
            </div>
        </div>

        <div class="tryout-duration-control">
            <label for="jumlah_soal">Jumlah Soal</label>
            <div>
                <input type="number" id="jumlah_soal" name="jumlah_soal" class="form-control"
                    min="1" max="500" value="{{ old('jumlah_soal', $tryoutPengaturan->jumlah_soal ?? 30) }}" required>
                <span>soal</span>
            </div>
        </div>
    </div>

    <div class="tryout-category-settings">
        <div class="tryout-category-settings-heading">
            <h6>Latihan Per Kategori</h6>
            <p>Atur jumlah soal dan durasi yang tampil saat peserta memilih latihan TWK, TIU, atau TKP.</p>
        </div>

        <div class="tryout-category-setting-grid">
            @foreach (['TWK' => 'Wawasan Kebangsaan', 'TIU' => 'Intelegensia Umum', 'TKP' => 'Karakteristik Pribadi'] as $kodeKategori => $namaKategori)
                <div class="tryout-category-setting-item">
                    <div>
                        <strong>{{ $kodeKategori }}</strong>
                        <span>{{ $namaKategori }}</span>
                    </div>

                    <label for="jumlah_soal_kategori_{{ strtolower($kodeKategori) }}">
                        Soal
                        <input type="number" id="jumlah_soal_kategori_{{ strtolower($kodeKategori) }}"
                            name="jumlah_soal_kategori[{{ $kodeKategori }}]" class="form-control"
                            min="1" max="500"
                            value="{{ old('jumlah_soal_kategori.' . $kodeKategori, $tryoutPengaturan->jumlahSoalKategori($kodeKategori)) }}" required>
                    </label>

                    <label for="durasi_menit_kategori_{{ strtolower($kodeKategori) }}">
                        Menit
                        <input type="number" id="durasi_menit_kategori_{{ strtolower($kodeKategori) }}"
                            name="durasi_menit_kategori[{{ $kodeKategori }}]" class="form-control"
                            min="1" max="300"
                            value="{{ old('durasi_menit_kategori.' . $kodeKategori, $tryoutPengaturan->durasiMenitKategori($kodeKategori)) }}" required>
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    <div class="tryout-setting-toggles">
        <label class="cat-toggle">
            <input type="hidden" name="acak_soal" value="0">
            <input type="checkbox" name="acak_soal" value="1" {{ $tryoutPengaturan->acak_soal ? 'checked' : '' }}>
            <span></span>
            Acak Soal
        </label>

        <label class="cat-toggle">
            <input type="hidden" name="acak_seimbang_kategori" value="0">
            <input type="checkbox" name="acak_seimbang_kategori" value="1" {{ $tryoutPengaturan->acak_seimbang_kategori ? 'checked' : '' }}>
            <span></span>
            Seimbang Kategori
        </label>

        <label class="cat-toggle">
            <input type="hidden" name="acak_jawaban" value="0">
            <input type="checkbox" name="acak_jawaban" value="1" {{ $tryoutPengaturan->acak_jawaban ? 'checked' : '' }}>
            <span></span>
            Acak Jawaban
        </label>
    </div>

    <div class="tryout-setting-action">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Simpan Pengaturan
        </button>
    </div>
</form>
