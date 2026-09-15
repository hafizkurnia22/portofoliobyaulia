<fieldset class="col-12 mb-3">
    <legend class="fs-6">Jenis pelajaran</legend>
    <p class="text-muted small">Centang topik yang dibahas dalam materi, sesuai kategori tes yang dipilih.</p>
    @foreach (\App\Models\TryoutMateri::TOPIK as $kode => $topics)
        <div class="mb-2">
            <strong class="d-block mb-1">{{ $kode }}</strong>
            @foreach ($topics as $topic)
                <label class="d-inline-flex align-items-center me-3 mb-2 gap-2">
                    <input type="checkbox" name="topik[]" value="{{ $topic }}" @checked(in_array($topic, $selectedTopics))>
                    <span>{{ $topic }}</span>
                </label>
            @endforeach
        </div>
    @endforeach
</fieldset>
