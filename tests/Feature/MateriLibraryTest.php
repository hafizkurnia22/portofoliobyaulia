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
        $materi = new TryoutMateri(['judul' => 'Nasionalisme', 'ringkasan' => '<b>Belajar</b>', 'isi_materi' => '<h2>Konsep</h2><p>Belajar bersama.</p>', 'topik' => ['Nasionalisme']]);
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

    public function test_curriculum_covers_all_21_topics_with_examples_and_explanations(): void
    {
        $lessons = require database_path('seeders/data/materi-skd-2024.php');
        $this->assertCount(21, $lessons);
        $this->assertSame(['TWK' => 5, 'TIU' => 10, 'TKP' => 6], array_count_values(array_column($lessons, 'kategori')));
        $this->assertCount(21, array_unique(array_column($lessons, 'topik')));
        foreach ($lessons as $lesson) {
            $this->assertContains($lesson['topik'], TryoutMateri::TOPIK[$lesson['kategori']]);
            $this->assertArrayHasKey('Tujuan belajar', $lesson['bagian']);
            $this->assertArrayHasKey('Konsep kunci', $lesson['bagian']);
            $this->assertArrayHasKey('Contoh latihan', $lesson['bagian']);
            $this->assertArrayHasKey('Pembahasan', $lesson['bagian']);
            $this->assertArrayHasKey('Catatan penting', $lesson['bagian']);
            $this->assertGreaterThanOrEqual(7, count($lesson['bagian']));
            foreach ($lesson['bagian'] as $content) {
                $this->assertNotEmpty($content);
            }
        }
    }

    public function test_reader_renders_content_and_return_to_learning_links(): void
    {
        $materi = new TryoutMateri(['judul' => 'Nasionalisme', 'ringkasan' => 'Belajar', 'isi_materi' => '<h2>Konsep</h2><p>Penjelasan.</p>', 'topik' => ['Nasionalisme']]);
        $materi->setRelation('kategoriSoal', new TryoutKategoriSoal(['kode' => 'TWK', 'nama' => 'Tes Wawasan Kebangsaan']));
        $html = view('pages.tryout-materi-detail', ['materi' => $materi, 'materiLainnya' => collect()])->render();
        $this->assertStringContainsString('readingContentsList', $html);
        $this->assertStringContainsString('<h2>Konsep</h2>', $html);
        $this->assertStringContainsString('tab=materi', $html);
        $this->assertStringContainsString('tab=simulasi', $html);
    }
}
