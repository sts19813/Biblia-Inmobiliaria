<?php

use App\Http\Controllers\Admin\AdvisorDevelopmentCatalogController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeveloperProfileController;
use App\Http\Controllers\Admin\DevelopmentComparisonController;
use App\Http\Controllers\Admin\DevelopmentController;
use App\Http\Controllers\Admin\DevelopmentDocumentController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PublicDevelopmentDocumentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

    Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
    Route::get('auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
    Route::get('google-auth/callback', [GoogleAuthController::class, 'callback']);
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('mini-drive/{token}', [PublicDevelopmentDocumentController::class, 'index'])->name('public.documents.index');
Route::get('mini-drive/{token}/carpetas/{folder}', [PublicDevelopmentDocumentController::class, 'folder'])->name('public.documents.folder');
Route::get('mini-drive/{token}/archivo/{file}/ver', [PublicDevelopmentDocumentController::class, 'viewFile'])->name('public.documents.files.view');
Route::get('mini-drive/{token}/archivo/{file}/descargar', [PublicDevelopmentDocumentController::class, 'download'])->name('public.documents.files.download');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::get('catalogo-desarrollos', [AdvisorDevelopmentCatalogController::class, 'index'])->name('advisor-catalog.index');
    Route::get('comparador-desarrollos', [DevelopmentComparisonController::class, 'index'])->name('development-comparison.index');
    Route::post('comparador-desarrollos/seleccion', [DevelopmentComparisonController::class, 'updateSelection'])->name('development-comparison.selection.update');
    Route::delete('comparador-desarrollos/seleccion', [DevelopmentComparisonController::class, 'clear'])->name('development-comparison.selection.clear');
    Route::delete('comparador-desarrollos/seleccion/{selection}', [DevelopmentComparisonController::class, 'remove'])->name('development-comparison.selection.remove');
    Route::get('desarrollos', [DevelopmentController::class, 'index'])->name('developments.index');
    Route::get('desarrollos/crear', [DevelopmentController::class, 'create'])->name('developments.create');
    Route::post('desarrollos', [DevelopmentController::class, 'store'])->name('developments.store');
    Route::get('desarrollos/{development}', [DevelopmentController::class, 'show'])->name('developments.show');
    Route::get('desarrollos/{development}/editar', [DevelopmentController::class, 'edit'])->name('developments.edit');
    Route::match(['put', 'patch'], 'desarrollos/{development}', [DevelopmentController::class, 'update'])->name('developments.update');
    Route::delete('desarrollos/{development}', [DevelopmentController::class, 'destroy'])->name('developments.destroy');
    Route::get('desarrollos/{development}/documentos', [DevelopmentDocumentController::class, 'index'])->name('developments.documents.index');
    Route::post('desarrollos/{development}/documentos/carpetas', [DevelopmentDocumentController::class, 'storeFolder'])->name('developments.documents.folders.store');
    Route::post('desarrollos/{development}/documentos/carpetas/{folder}/archivos', [DevelopmentDocumentController::class, 'upload'])->name('developments.documents.files.upload');
    Route::patch('desarrollos/{development}/documentos/archivos/{file}/renombrar', [DevelopmentDocumentController::class, 'renameFile'])->name('developments.documents.files.rename');
    Route::patch('desarrollos/{development}/documentos/archivos/{file}/destacado', [DevelopmentDocumentController::class, 'toggleFeatured'])->name('developments.documents.files.featured');
    Route::patch('desarrollos/{development}/documentos/archivos/{file}/visibilidad', [DevelopmentDocumentController::class, 'toggleVisibility'])->name('developments.documents.files.visibility');
    Route::delete('desarrollos/{development}/documentos/archivos/{file}', [DevelopmentDocumentController::class, 'destroyFile'])->name('developments.documents.files.destroy');
    Route::post('desarrollos/{development}/documentos/carpetas/{folder}/permisos', [DevelopmentDocumentController::class, 'updatePermissions'])->name('developments.documents.permissions.update');
    Route::resource('usuarios', UserController::class)
        ->except(['show'])
        ->names('users')
        ->parameters(['usuarios' => 'user']);
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
    Route::patch('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::post('permisos', [PermissionController::class, 'store'])->name('permissions.store');
    Route::delete('permisos/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    Route::prefix('catalogos')->name('catalogs.')->group(function () {
        Route::get('amenidades', [AmenityController::class, 'index'])->name('amenities.index');
        Route::post('amenidades', [AmenityController::class, 'store'])->name('amenities.store');
        Route::patch('amenidades/{amenity}', [AmenityController::class, 'update'])->name('amenities.update');
        Route::delete('amenidades/{amenity}', [AmenityController::class, 'destroy'])->name('amenities.destroy');
    });
    Route::get('perfil-desarrolladora', [DeveloperProfileController::class, 'index'])->name('developer-profile');
    Route::get('perfil-desarrolladora/crear', [DeveloperProfileController::class, 'create'])->name('developer-profile.create');
    Route::post('perfil-desarrolladora', [DeveloperProfileController::class, 'store'])->name('developer-profile.store');
    Route::get('perfil-desarrolladora/{developerProfile}', [DeveloperProfileController::class, 'show'])->name('developer-profile.show');
    Route::get('perfil-desarrolladora/{developerProfile}/editar', [DeveloperProfileController::class, 'edit'])->name('developer-profile.edit');
    Route::match(['put', 'patch'], 'perfil-desarrolladora/{developerProfile}', [DeveloperProfileController::class, 'update'])->name('developer-profile.update');
    Route::delete('perfil-desarrolladora/{developerProfile}', [DeveloperProfileController::class, 'destroy'])->name('developer-profile.destroy');
    Route::get('configuraciones', [SettingsController::class, 'index'])->name('settings');
    Route::patch('configuraciones', [SettingsController::class, 'update'])->name('settings.update');
});
