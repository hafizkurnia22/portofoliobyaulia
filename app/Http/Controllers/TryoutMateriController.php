<?php

namespace App\Http\Controllers;

use App\Models\TryoutMateri;
use Illuminate\Http\Request;

class TryoutMateriController extends Controller
{
    public function store(Request $request)
    {
        TryoutMateri::create($this->validatedData($request));

        return redirect('/admin/dashboard')
            ->with('success', 'Materi ujian berhasil ditambahkan')
            ->with('active_tab', 'master-materi-tryout');
    }

    public function update(Request $request, $id)
    {
        $materi = TryoutMateri::findOrFail($id);
        $materi->update($this->validatedData($request));

        return redirect('/admin/dashboard')
            ->with('success', 'Materi ujian berhasil diupdate')
            ->with('active_tab', 'master-materi-tryout');
    }

    public function destroy($id)
    {
        TryoutMateri::findOrFail($id)->delete();

        return redirect('/admin/dashboard')
            ->with('success', 'Materi ujian berhasil dihapus')
            ->with('active_tab', 'master-materi-tryout');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'tryout_kategori_soal_id' => 'required|exists:tryout_kategori_soals,id',
            'judul' => 'required|string|max:150',
            'ringkasan' => 'nullable|string|max:500',
            'isi_materi' => 'required|string',
            'status' => 'required|in:aktif,draft',
            'topik' => 'nullable|array',
            'topik.*' => ['string', 'distinct', \Illuminate\Validation\Rule::in(array_merge(...array_values(TryoutMateri::TOPIK)))],
        ]);

        $kategori = \App\Models\TryoutKategoriSoal::findOrFail($data['tryout_kategori_soal_id']);
        $data['topik'] = array_values($data['topik'] ?? []);
        if (array_diff($data['topik'], TryoutMateri::TOPIK[$kategori->kode] ?? [])) {
            throw \Illuminate\Validation\ValidationException::withMessages(['topik' => 'Pilih topik yang sesuai dengan kategori tes.']);
        }

        $data['ringkasan'] = $data['ringkasan'] ? trim(strip_tags($data['ringkasan'])) : null;
        $data['isi_materi'] = $this->sanitizeRichText($data['isi_materi']);

        return $data;
    }

    private function sanitizeRichText(string $value): string
    {
        $value = strip_tags($value, '<p><br><strong><b><em><i><u><ol><ul><li><a><h2><h3><blockquote>');
        $value = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $value);
        $value = preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $value);
        $value = preg_replace('/\s+(style|class|id)\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $value);
        $value = preg_replace('/<(?!a\b)([a-z0-9]+)(?:\s[^>]*)?>/i', '<$1>', $value);

        $value = preg_replace_callback('/<a\b([^>]*)>/i', function ($matches) {
            $href = '#';

            if (preg_match('/href\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', $matches[1], $hrefMatch)) {
                $href = trim($hrefMatch[1], '"\' ');
            }

            if (!preg_match('/^(https?:\/\/|mailto:)/i', $href)) {
                $href = '#';
            }

            return '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener">';
        }, $value);

        return trim($value);
    }
}
