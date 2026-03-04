<?php



use App\Models\Libur;
use Illuminate\Foundation\Testing\RefreshDatabase;

/*
|--------------------------------------------------------------------------
| GET /libur (manage)
|--------------------------------------------------------------------------
*/
it('menampilkan halaman manajemen libur', function () {
    Libur::factory()->create([
        'tanggal' => '2026-01-01',
    ]);

    $response = $this->get(route('libur.manage'));

    $response->assertStatus(200);
    $response->assertViewIs('settings.libur');
    $response->assertViewHas('libur');
});

/*
|--------------------------------------------------------------------------
| POST /libur (store)
|--------------------------------------------------------------------------
*/
it('dapat menambahkan data libur', function () {
    $response = $this->post(route('libur.store'), [
        'tanggal' => '2026-02-01',
        'keterangan' => 'Tahun Baru Imlek',
    ]);

    $response->assertRedirect(route('libur.manage'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('libur', [
        'tanggal' => '2026-02-01',
    ]);
});

/*
|--------------------------------------------------------------------------
| PUT /libur/{id} (update)
|--------------------------------------------------------------------------
*/
it('dapat mengupdate data libur', function () {
    $libur = Libur::factory()->create();

    $response = $this->put(route('libur.update', $libur->id), [
        'tanggal' => '2026-03-02',
        'keterangan' => 'Update libur',
    ]);

    $response->assertRedirect(route('libur.manage'));

    $this->assertDatabaseHas('libur', [
        'id' => $libur->id,
        'tanggal' => '2026-03-02',
    ]);
});

/*
|--------------------------------------------------------------------------
| DELETE /libur/{id} (destroy)
|--------------------------------------------------------------------------
*/
it('dapat menghapus data libur', function () {
    $libur = Libur::factory()->create();

    $response = $this->delete(route('libur.destroy', $libur->id));

    $response->assertRedirect(route('libur.manage'));

    $this->assertDatabaseMissing('libur', [
        'id' => $libur->id,
    ]);
});
