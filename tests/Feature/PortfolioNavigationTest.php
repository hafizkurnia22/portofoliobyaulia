<?php

namespace Tests\Feature;

use Tests\TestCase;

class PortfolioNavigationTest extends TestCase
{
    public function test_removed_tryout_routes_are_unavailable(): void
    {
        foreach ([
            '/tryout',
            '/tryout/login',
            '/tryout/materi/1',
            '/admin/tryout/pengaturan',
            '/admin/tryout/riwayat/1',
            '/template-import-tryout-soal',
        ] as $url) {
            $this->get($url)->assertNotFound();
        }

        $routes = collect(app('router')->getRoutes()->getRoutes());
        $this->assertFalse($routes->contains(fn ($route) => str_contains($route->uri(), 'tryout')));
    }

    public function test_portfolio_and_admin_pages_work_without_tryout(): void
    {
        foreach (['/', '/sertifikasi', '/cv-builder'] as $url) {
            $this->get($url)->assertOk()->assertDontSee('Tryout CPNS');
        }

        foreach (['dashboard', 'pengalaman', 'sertifikasi', 'skill', 'project', 'tentang', 'master-soal'] as $tab) {
            $this->withSession(['admin_login' => true])
                ->get('/admin/dashboard?active_tab=' . $tab)
                ->assertOk()
                ->assertDontSee('adminTryoutMenu')
                ->assertDontSee('simpan-tryout')
                ->assertDontSee('Total Soal');
        }
    }
}
