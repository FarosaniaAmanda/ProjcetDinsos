<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

class LaporanAuthorizationTest extends TestCase
{
    public function test_unauthenticated_users_are_redirected_from_report_routes(): void
    {
        $reportUrls = [
            route('laporan.index'),
            route('laporan.detail', ['uuid' => 'f77b4a1e-2e5a-4bdb-97cf-9f8c3935d10d']),
            route('laporan.pdf', ['uuid' => 'f77b4a1e-2e5a-4bdb-97cf-9f8c3935d10d']),
            route('admin.laporan.export'),
        ];

        foreach ($reportUrls as $reportUrl) {
            $this->get($reportUrl)
                ->assertRedirect(route('login'));
        }
    }

    public function test_numeric_primary_keys_do_not_match_report_routes(): void
    {
        $this->get('/laporan/73/detail')
            ->assertNotFound();

        $this->get('/laporan/73/pdf')
            ->assertNotFound();
    }
}
