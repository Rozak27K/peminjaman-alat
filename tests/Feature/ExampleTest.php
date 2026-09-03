<?php

namespace Tests\Feature;

use App\Models\Alat;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_can_be_displayed_after_login(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertSee('Aplikasi Peminjaman Alat');
    }

    public function test_admin_can_access_alat_page(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/alat');

        $response->assertSee('Data Alat');
    }

    public function test_peminjam_cannot_access_alat_page(): void
    {
        $peminjam = User::factory()->peminjam()->create();

        $response = $this->actingAs($peminjam)->get('/alat');

        $response->assertForbidden();
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => 'password',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'login@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_registered_user_becomes_peminjam(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Budi Peminjam',
            'email' => 'budi@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'budi@example.com',
            'role' => 'peminjam',
        ]);
        $this->assertAuthenticated();
    }

    public function test_peminjam_does_not_see_approval_buttons_on_submission_detail(): void
    {
        $peminjam = User::factory()->peminjam()->create();
        $peminjaman = Peminjaman::factory()->create([
            'peminjam_id' => $peminjam->id,
            'status' => 'diajukan',
        ]);
        DetailPeminjaman::factory()->create([
            'peminjaman_id' => $peminjaman->id,
        ]);

        $response = $this->actingAs($peminjam)->get(route('peminjaman.show', $peminjaman));

        $response->assertOk();
        $response->assertDontSee('Setujui');
        $response->assertDontSee('Tolak');
    }

    public function test_petugas_sees_approval_buttons_on_pending_submission_detail(): void
    {
        $petugas = User::factory()->petugas()->create();
        $peminjaman = Peminjaman::factory()->create(['status' => 'diajukan']);
        DetailPeminjaman::factory()->create([
            'peminjaman_id' => $peminjaman->id,
        ]);

        $response = $this->actingAs($petugas)->get(route('peminjaman.show', $peminjaman));

        $response->assertOk();
        $response->assertSee('Setujui');
        $response->assertSee('Tolak');
    }

    public function test_peminjam_submission_uses_authenticated_user(): void
    {
        $peminjam = User::factory()->peminjam()->create();
        $otherPeminjam = User::factory()->peminjam()->create();
        $alat = Alat::factory()->create(['stok_tersedia' => 3]);

        $response = $this->actingAs($peminjam)->post(route('peminjaman.store'), [
            'peminjam_id' => $otherPeminjam->id,
            'alat_id' => $alat->id,
            'jumlah' => 1,
            'tanggal_rencana_kembali' => now()->addWeek()->toDateString(),
            'keperluan' => 'Praktikum',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('peminjaman', [
            'peminjam_id' => $peminjam->id,
            'keperluan' => 'Praktikum',
            'status' => 'diajukan',
        ]);
        $this->assertDatabaseMissing('peminjaman', [
            'peminjam_id' => $otherPeminjam->id,
            'keperluan' => 'Praktikum',
        ]);
    }

    public function test_admin_and_petugas_cannot_create_submission(): void
    {
        $admin = User::factory()->admin()->create();
        $petugas = User::factory()->petugas()->create();
        $alat = Alat::factory()->create(['stok_tersedia' => 3]);

        $this->actingAs($admin)
            ->get(route('peminjaman.create'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->post(route('peminjaman.store'), [
                'alat_id' => $alat->id,
                'jumlah' => 1,
                'tanggal_rencana_kembali' => now()->addWeek()->toDateString(),
                'keperluan' => 'Inventaris',
            ])
            ->assertForbidden();

        $this->actingAs($petugas)
            ->get(route('peminjaman.create'))
            ->assertForbidden();

        $this->actingAs($petugas)
            ->post(route('peminjaman.store'), [
                'alat_id' => $alat->id,
                'jumlah' => 1,
                'tanggal_rencana_kembali' => now()->addWeek()->toDateString(),
                'keperluan' => 'Inventaris',
            ])
            ->assertForbidden();
    }

    public function test_petugas_sees_colored_decision_actions_on_peminjaman_index(): void
    {
        $petugas = User::factory()->petugas()->create();
        Peminjaman::factory()->create(['status' => 'diajukan']);

        $response = $this->actingAs($petugas)->get(route('peminjaman.index'));

        $response->assertOk();
        $response->assertSee('Terima');
        $response->assertSee('Tidak');
        $response->assertSee('Detail');
        $response->assertDontSee('Ajukan peminjaman');
    }

    public function test_admin_does_not_see_submission_create_button(): void
    {
        $admin = User::factory()->admin()->create();
        Peminjaman::factory()->create(['status' => 'diajukan']);

        $response = $this->actingAs($admin)->get(route('peminjaman.index'));

        $response->assertOk();
        $response->assertDontSee('Ajukan peminjaman');
        $response->assertDontSee('Terima');
        $response->assertDontSee('Tidak');
        $response->assertSee('Detail');
    }
}
