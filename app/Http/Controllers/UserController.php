<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('users.index', [
            'users' => User::query()->latest()->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('users.form', ['user' => new User]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());

        $user = User::query()->create($data);
        $this->log('create', $user, null, $user->only(['id', 'name', 'email', 'role']));

        return redirect()->route('users.index')->with('status', 'User dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        return view('users.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        return view('users.form', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate($this->rules($user));
        $oldData = $user->only(['id', 'name', 'email', 'role', 'telepon', 'alamat']);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);
        $this->log('update', $user, $oldData, $user->fresh()->only(['id', 'name', 'email', 'role', 'telepon', 'alamat']));

        return redirect()->route('users.index')->with('status', 'User diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $oldData = $user->only(['id', 'name', 'email', 'role']);
        $user->delete();
        $this->log('delete', $user, $oldData);

        return redirect()->route('users.index')->with('status', 'User dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(?User $user = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['admin', 'petugas', 'peminjam'])],
            'telepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
        ];
    }

    private function log(string $aksi, User $user, ?array $dataLama = null, ?array $dataBaru = null): void
    {
        LogAktivitas::query()->create([
            'aksi' => $aksi,
            'modul' => 'user',
            'subjek_type' => User::class,
            'subjek_id' => $user->id,
            'data_lama' => $dataLama,
            'data_baru' => $dataBaru,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
