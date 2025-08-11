<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Models\Alternatif;
use App\Policies\AlternatifPolicy;

// Jika sudah ada, buka komentar berikut:
// use App\Models\Penilaian;
// use App\Policies\PenilaianPolicy;
// use App\Models\NilaiAkhir;
// use App\Policies\NilaiAkhirPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Map model ke policy.
     * Pastikan setiap model yang ingin dibatasi punya policy-nya.
     */
    protected $policies = [
        Alternatif::class => AlternatifPolicy::class,
        // Penilaian::class  => PenilaianPolicy::class,
        // NilaiAkhir::class => NilaiAkhirPolicy::class,
    ];

    /**
     * Bootstrap auth services.
     */
    public function boot(): void
    {
        // Daftarkan semua policy di atas
        $this->registerPolicies();

        // Admin bypass: admin lolos semua ability tanpa cek policy
        Gate::before(function (User $user, string $ability) {
            // pakai helper isAdmin() kalau sudah kamu buat di User.php
            return method_exists($user, 'isAdmin')
                ? ($user->isAdmin() ? true : null)
                : ($user->role === 'admin' ? true : null);
        });

        // (Opsional) Gate umum—walaupun kebanyakan sudah tertangani oleh policy resource
        Gate::define('view-any-siswa', function (User $user) {
            return (method_exists($user,'isAdmin') && $user->isAdmin())
                || (method_exists($user,'isWaliKelas') && $user->isWaliKelas())
                || in_array($user->role, ['admin','wali_kelas']);
        });
    }
}
