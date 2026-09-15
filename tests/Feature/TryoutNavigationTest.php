<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use Tests\TestCase;

class TryoutNavigationTest extends TestCase
{
    public function test_history_is_only_rendered_on_the_tryout_home_page(): void
    {
        foreach (['menu', 'ujian', 'materi', 'invalid'] as $mode) {
            $this->app->instance('request', Request::create('/tryout', 'GET', ['mode' => $mode]));

            $html = view('pages.tryout', [
                'peserta' => (object) ['nama' => 'Peserta Uji', 'username' => 'peserta-uji'],
                'soals' => collect([[
                    'id' => 1, 'kategori' => 'TWK', 'pertanyaan' => 'Contoh soal',
                    'opsi' => ['A' => 'Satu', 'B' => 'Dua'],
                    'jawaban_benar' => 'A', 'skor' => ['A' => 5, 'B' => 0], 'pembahasan' => '',
                ]]),
                'tryoutPengaturan' => (object) ['durasi_menit' => 45, 'acak_soal' => false, 'acak_jawaban' => false],
                'riwayatTryout' => collect(),
                'materiTryout' => collect(),
            ])->render();

            if (in_array($mode, ['menu', 'invalid'], true)) {
                $this->assertStringContainsString('id="tryoutHistoryCard"', $html);
                $this->assertStringContainsString('Belum ada riwayat.', $html);
            } else {
                $this->assertStringNotContainsString('id="tryoutHistoryCard"', $html);
            }

            if ($mode === 'ujian') {
                $this->assertStringNotContainsString('id="navbarMenu"', $html);
                $this->assertStringContainsString('class="tryout-exam-page"', $html);
                $this->assertStringContainsString('class="cat-exam-toolbar"', $html);
                $this->assertStringNotContainsString('class="tryout-header"', $html);
                $this->assertStringNotContainsString('class="tryout-mode-actions"', $html);
                $this->assertStringNotContainsString('class="floating-whatsapp"', $html);
                $this->assertStringNotContainsString('id="examMaterialLink"', $html);
                $this->assertStringContainsString('Beranda Tryout', $html);
                $this->assertStringContainsString('id="examPreparation"', $html);
                $this->assertStringContainsString('class="cat-shell d-none"', $html);
                $this->assertStringContainsString('id="catFinishModal"', $html);
            } else {
                $this->assertStringContainsString('id="navbarMenu"', $html);
                $this->assertStringNotContainsString('id="examShell"', $html);
            }
        }
    }
}
