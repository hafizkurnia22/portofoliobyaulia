<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Generate CV saya');
        $response->assertDontSee('Download CV');
    }

    public function test_cv_builder_returns_a_successful_response(): void
    {
        $response = $this->get('/cv-builder');

        $response->assertStatus(200);
        $response->assertSee('Generate CV saya');
    }

    public function test_cv_download_accepts_builder_options(): void
    {
        $response = $this->get('/download-cv?builder=1&template=compact&theme=emerald&show_photo=0&show_profile=1&show_skills=1&show_experience=1&show_certifications=1');

        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
    }
}
