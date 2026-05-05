<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OdontologoConfigController;
use App\Http\Controllers\RRHHController;
use App\Http\Controllers\EstrategicoController;

// Soporte
use App\Http\Controllers\Soporte\FinanzasController;
use App\Http\Controllers\Soporte\AdministracionController;
use App\Http\Controllers\Soporte\PagoController;

// Historia clínica
use App\Http\Controllers\HistoriaClinicaController;
use App\Http\Controllers\NotaClinicaController;

// Operativos
use App\Http\Controllers\OperativosController;

// Horarios fijos (RRHH)
use App\Http\Controllers\HorarioController;

// Este archivo conecta las URL del sistema con sus controladores.
// La mayor parte del sistema queda dentro del middleware auth + roles.

// Página raíz → dashboard
Route::get('/', fn() => redirect()->route('dashboard'));

// =============================================================
// 🔐 BLOQUE PROTEGIDO: solo personal con sesión y rol autorizado
// =============================================================
Route::middleware(['auth', 'role:admin|recepcionista|odontologo|asistente'])->group(function () {

    // ========================
    //         DASHBOARD
    // ========================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ========================
    //         PERFIL
    // ========================
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ========================
    //         PACIENTES
    // ========================
    Route::resource('pacientes', PacienteController::class)
        ->parameters(['pacientes' => 'paciente']);

    // ========================
    //           CITAS
    // ========================
    Route::get('citas/odontologos-disponibles', [CitaController::class, 'disponibles'])
        ->name('citas.odontologos.disponibles');

    Route::resource('citas', CitaController::class)
        ->except(['show'])
        ->parameters(['citas' => 'cita']);

    // Acciones especiales del flujo de atencion de citas.
    Route::patch('citas/{cita}/reprogramar', [CitaController::class, 'reprogram'])->name('citas.reprogram');
    Route::patch('citas/{cita}/cancelar',    [CitaController::class, 'cancel'])->name('citas.cancel');
    Route::patch('citas/{cita}/checkin',     [CitaController::class, 'checkin'])->name('citas.checkin');
    Route::get('citas/{cita}/atender',       [CitaController::class, 'atender'])->name('citas.atender');

    // ========================
    //          RRHH
    // ========================
    Route::get('rrhh', [RRHHController::class, 'index'])
        ->middleware('role:admin|recepcionista')
        ->name('rrhh.index');

    Route::middleware(['role:admin|recepcionista'])->group(function () {
        Route::get('odontologos/config', [OdontologoConfigController::class, 'index'])
            ->name('odontologos.config');

        Route::post('odontologos/disponibilidad',            [OdontologoConfigController::class, 'addDisponibilidad'])->name('odontologos.disponibilidad.add');
        Route::delete('odontologos/disponibilidad/{bloque}', [OdontologoConfigController::class, 'delDisponibilidad'])->name('odontologos.disponibilidad.del');

        Route::post('odontologos/ausencia',                  [OdontologoConfigController::class, 'addAusencia'])->name('odontologos.ausencia.add');
        Route::delete('odontologos/ausencia/{ausencia}',     [OdontologoConfigController::class, 'delAusencia'])->name('odontologos.ausencia.del');

        Route::post('odontologos/asistentes',                [OdontologoConfigController::class, 'attachAssistant'])->name('odontologos.asistentes.attach');
        Route::patch('odontologos/asistentes/{pivot}',       [OdontologoConfigController::class, 'updateAssistantTurno'])->name('odontologos.asistentes.update');
        Route::delete('odontologos/asistentes/{pivot}',      [OdontologoConfigController::class, 'detachAssistant'])->name('odontologos.asistentes.detach');
    });

    // ========================
    //      HORARIOS FIJOS (RRHH)
    // ========================
    Route::middleware(['role:admin|recepcionista'])->group(function () {
        Route::get('odontologos/horarios',              [HorarioController::class, 'index'])->name('odontologos.horarios.index');
        Route::post('odontologos/horarios',             [HorarioController::class, 'store'])->name('odontologos.horarios.store');
        Route::delete('odontologos/horarios/{horario}', [HorarioController::class, 'destroy'])->name('odontologos.horarios.destroy');
    });

    // ========================
    //    PROCESOS ESTRATÉGICOS
    // ========================
    Route::prefix('estrategicos')->group(function () {
        Route::get('/',             [EstrategicoController::class, 'index'])->name('estrategicos.index');
        Route::get('/planeamiento', [EstrategicoController::class, 'planeamiento'])->name('estrategicos.planeamiento');
        Route::get('/calidad',      [EstrategicoController::class, 'calidad'])->name('estrategicos.calidad');
        Route::get('/innovacion',   [EstrategicoController::class, 'innovacion'])->name('estrategicos.innovacion');
    });

    // ========================
    //     PROCESOS DE SOPORTE
    // ========================
    Route::prefix('soporte')->as('soporte.')->group(function () {
        // Finanzas (admin | recepcionista)
        Route::middleware('role:admin|recepcionista')->group(function () {
            Route::get('/finanzas',                 [FinanzasController::class, 'index'])->name('finanzas.index');
            Route::get('/finanzas/pagos/crear',     [PagoController::class, 'create'])->name('finanzas.pagos.create');
            Route::post('/finanzas/pagos',          [PagoController::class, 'store'])->name('finanzas.pagos.store');
            Route::get('/finanzas/pagos/registrados', [PagoController::class, 'index'])->name('finanzas.pagos.index'); // 👈 NUEVA
        });

        // Administración (admin | recepcionista | asistente)
        Route::middleware('role:admin|recepcionista|asistente')->group(function () {
            Route::get('/administracion', [AdministracionController::class, 'index'])->name('administracion.index');
        });
    });

    // ========================
    //     PROCESOS OPERATIVOS
    // ========================
    Route::prefix('operativos')->group(function () {
        Route::get('/diagnostico',  [OperativosController::class, 'diagnostico'])->name('operativos.diagnostico');
        Route::get('/tratamiento',  [OperativosController::class, 'tratamiento'])->name('operativos.tratamiento');
        Route::get('/seguimiento',  [OperativosController::class, 'seguimiento'])->name('operativos.seguimiento');
    });

    // ========================
    //        HISTORIA CLÍNICA
    // ========================
    Route::prefix('pacientes')->group(function () {
        // La historia se consulta desde el paciente porque es informacion clinica personal.
        Route::get('{paciente}/historia',       [HistoriaClinicaController::class, 'showByPaciente'])->name('historias.show');
        Route::get('{paciente}/historia/crear', [HistoriaClinicaController::class, 'create'])->name('historias.create');
        Route::post('{paciente}/historia',      [HistoriaClinicaController::class, 'store'])->name('historias.store');
    });

    Route::put('historias/{historia}',        [HistoriaClinicaController::class, 'update'])->name('historias.update');
    Route::put('historias/{historia}/cerrar', [HistoriaClinicaController::class, 'cerrar'])->name('historias.cerrar');

    // ========================
    //         NOTAS CLÍNICAS
    // ========================
    Route::get('historias/{historia}/notas/crear', [NotaClinicaController::class, 'create'])->name('notas.create');
    Route::post('historias/{historia}/notas',      [NotaClinicaController::class, 'store'])->name('notas.store');
    Route::delete('notas/{nota}',                  [NotaClinicaController::class, 'destroy'])->name('notas.destroy');

    // ========================
    //     USUARIOS (solo admin)
    // ========================
    Route::resource('users', UserController::class)
        ->middleware('role:admin')
        ->parameters(['users' => 'user']);
});

// =============================================================
//  RUTAS DE AUTENTICACIÓN (Breeze / Laravel Breeze)
// =============================================================
require __DIR__ . '/auth.php';
