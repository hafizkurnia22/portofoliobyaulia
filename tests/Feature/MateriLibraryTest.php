<?php

namespace Tests\Feature;

use App\Models\TryoutMateri;
use App\Models\TryoutKategoriSoal;
use Tests\TestCase;

class MateriLibraryTest extends TestCase
{
    public function test_legacy_titles_only_receive_matching_topics_and_explicit_choices_win(): void
    {
        $materi = new TryoutMateri(['judul' => 'Pilar Kebangsaan dan Nasionalisme']);
        $materi->setRelation('kategoriSoal', new TryoutKategoriSoal(['kode' => 'TWK']));
        $this->assertSame(['Nasionalisme', 'Pilar Negara'], $materi->topikPelajaran());
        $materi->topik = ['Integritas'];
        $this->assertSame(['Integritas'], $materi->topikPelajaran());
        $materi->topik = [];
        $this->assertSame([], $materi->topikPelajaran());
    }

    public function test_library_renders_topic_filters_and_safe_reading_links(): void
    {
        $materi = new TryoutMateri(['judul' => 'Nasionalisme', 'ringkasan' => '<b>Belajar</b>', 'topik' => ['Nasionalisme']]);
        $materi->id = 123;
        $materi->setRelation('kategoriSoal', new TryoutKategoriSoal(['kode' => 'TWK']));
        $html = view('components.materi-library', [
            'allMateri' => collect([$materi]), 'kategoriMateriOptions' => collect(['TWK']),
        ])->render();
        $this->assertStringContainsString('Jenis pelajaran', $html);
        $this->assertStringContainsString('Kemampuan Figural', $html);
        $this->assertStringContainsString('/tryout/materi/123', $html);
        $this->assertStringNotContainsString('<table', $html);
        $this->assertStringNotContainsString('<b>Belajar</b>', $html);
    }
}
