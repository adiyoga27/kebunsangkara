<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Str;

it('groups the sidebar menu and shows every section to administrators', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSeeInOrder(['Dashboard', 'Katalog', 'Hasil Perkebunan', 'Bibit', 'Pupuk', 'Obat Pertanian', 'Produk Kesehatan', 'Kategori Buah', 'Sampah Produk', 'Konten', 'Artikel', 'Laporan', 'Klik WhatsApp', 'Sistem', 'Pengaturan']);
});

it('hides the report and system menus from editors', function () {
    $editor = User::factory()->create(['role' => UserRole::Editor]);

    $this->actingAs($editor)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee(route('admin.articles.index'))
        ->assertDontSee([route('admin.whatsapp-clicks.index'), route('admin.activity-logs.index'), route('admin.users.index'), route('admin.settings.edit')]);
});

it('marks exactly one sidebar menu as the current page', function (string $route, array $parameters, string $label) {
    $html = $this->actingAs(User::factory()->create(['role' => UserRole::Admin]))
        ->get(route($route, $parameters))
        ->assertOk()
        ->getContent();
    $sidebar = Str::between($html, 'id="admin-sidebar"', '</aside>');

    expect(substr_count($sidebar, 'aria-current="page"'))->toBe(1);
    expect(strip_tags(Str::between($sidebar, 'aria-current="page"', '</li>')))->toContain($label);
})->with([
    'dashboard' => ['admin.dashboard', [], 'Dashboard'],
    'products without a type' => ['admin.products.index', [], 'Hasil Perkebunan'],
    'fertilizer' => ['admin.products.index', ['type' => 'pupuk'], 'Pupuk'],
    'crop protection' => ['admin.products.index', ['type' => 'obat'], 'Obat Pertanian'],
    'new seedling' => ['admin.products.create', ['type' => 'bibit'], 'Bibit'],
    'trash' => ['admin.trashed-products.index', [], 'Sampah Produk'],
    'settings' => ['admin.settings.edit', [], 'Pengaturan'],
]);
