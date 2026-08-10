<?php

namespace App\Http\Controllers;

use App\Models\Pengalaman;
use App\Models\Project;
use App\Models\Sertifikasi;
use App\Models\Skill;
use App\Models\TentangSaya;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CvBuilderController extends Controller
{
    private const DEFAULT_TEMPLATE = 'modern';
    private const DEFAULT_THEME = 'navy';

    /**
     * Tema warna dipakai bersama oleh preview web dan file PDF.
     * Kalau mau menambah tema baru, cukup tambahkan data di sini.
     */
    private const THEMES = [
        'navy' => [
            'label' => 'Navy Professional',
            'primary' => '#0b1f3a',
            'secondary' => '#2563eb',
            'soft' => '#dbeafe',
            'muted' => '#bfdbfe',
        ],
        'emerald' => [
            'label' => 'Emerald Fresh',
            'primary' => '#064e3b',
            'secondary' => '#10b981',
            'soft' => '#d1fae5',
            'muted' => '#a7f3d0',
        ],
        'plum' => [
            'label' => 'Plum Creative',
            'primary' => '#581c87',
            'secondary' => '#a855f7',
            'soft' => '#f3e8ff',
            'muted' => '#e9d5ff',
        ],
        'charcoal' => [
            'label' => 'Charcoal Minimal',
            'primary' => '#111827',
            'secondary' => '#64748b',
            'soft' => '#e5e7eb',
            'muted' => '#cbd5e1',
        ],
    ];

    private const TEMPLATES = [
        'modern',
        'compact',
    ];

    public function index(Request $request)
    {
        return view('pages.cv-builder', $this->getCvData($request));
    }

    public function download(Request $request)
    {
        $data = $this->getCvData($request);

        $pdf = Pdf::loadView('pages.cv-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        $namaFile = Str::slug($data['tentangSaya']->nama ?? 'portfolio') ?: 'portfolio';

        return $pdf->download('CV-' . $namaFile . '-' . $data['cvOptions']['theme'] . '.pdf');
    }

    private function getCvData(Request $request): array
    {
        return [
            'tentangSaya' => TentangSaya::first(),
            'pengalaman' => Pengalaman::byLatestYear()->get(),
            'projects' => Project::latest()->get(),
            'skill' => Skill::latest()->get(),
            'sertifikasi' => Sertifikasi::byLatestYear()->get(),
            'cvOptions' => $this->resolveOptions($request),
            'cvThemes' => self::THEMES,
        ];
    }

    private function resolveOptions(Request $request): array
    {
        $template = $request->query('template', self::DEFAULT_TEMPLATE);
        $theme = $request->query('theme', self::DEFAULT_THEME);

        if (! in_array($template, self::TEMPLATES, true)) {
            $template = self::DEFAULT_TEMPLATE;
        }

        if (! array_key_exists($theme, self::THEMES)) {
            $theme = self::DEFAULT_THEME;
        }

        /**
         * Saat halaman dibuka pertama kali, semua bagian CV aktif.
         * Setelah form builder dipakai, checkbox yang tidak dicentang bernilai false.
         */
        $isBuilderRequest = $request->boolean('builder', false);
        $show = function (string $key) use ($request, $isBuilderRequest): bool {
            return $isBuilderRequest ? $request->boolean($key) : true;
        };

        return [
            'template' => $template,
            'theme' => $theme,
            'theme_label' => self::THEMES[$theme]['label'],
            'colors' => self::THEMES[$theme],
            'show_photo' => $show('show_photo'),
            'show_profile' => $show('show_profile'),
            'show_skills' => $show('show_skills'),
            'show_experience' => $show('show_experience'),
            'show_projects' => $show('show_projects'),
            'show_certifications' => $show('show_certifications'),
        ];
    }
}
