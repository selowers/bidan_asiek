<?php

use App\Http\Controllers\AdminStoreController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\KatalogLayananController;
use App\Http\Controllers\LayananKlinikController;
use App\Http\Controllers\JanjiTemuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\VideoController;
use App\Models\JanjiTemu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pasien;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:login')->name('login.store');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/forgot-password', [PasswordController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'sendLink'])->middleware('throttle:password-reset')->name('password.email');
    Route::get('/reset-password/{token}', [PasswordController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/', function (Request $request) {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user instanceof User || ! $user->isAdmin()) {
            return to_route('profile');
        }

        $period = $request->query('period', 'month');
        $metric = $request->query('metric', 'income');
        $metric = in_array($metric, ['income', 'patients', 'appointments'], true) ? $metric : 'income';
        $selectedYear = (int) $request->query('year', now()->year);
        $yearOptions = range(now()->year - 4, now()->year);
        $selectedYear = in_array($selectedYear, $yearOptions, true) ? $selectedYear : now()->year;
        $selectedMonth = $request->query('month', now()->format('Y-m'));
        $isAnnual = $period === 'year';
        $monthDate = preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $selectedMonth)
            ? Carbon::createFromFormat('Y-m-d', $selectedMonth.'-01')->startOfMonth()
            : now()->startOfMonth();
        if ($isAnnual) {
            $monthDate = Carbon::create($selectedYear, 1, 1, 0, 0, 0)->startOfYear();
        }
        $selectedMonth = $monthDate->format('Y-m');
        $startDate = $isAnnual ? $monthDate->copy()->startOfYear() : $monthDate->copy()->startOfMonth();
        $endDate = $isAnnual ? $monthDate->copy()->endOfYear() : $monthDate->copy()->endOfMonth();

        try {
            $patientCount = Pasien::whereBetween('created_at', [$startDate, $endDate])->count();
            $upcomingAppointmentCount = JanjiTemu::whereBetween('appointment_at', [$startDate, $endDate])
                ->whereIn('status', ['scheduled', 'confirmed'])
                ->count();

            $labels = [];
            $revenueData = [];
            $patientData = [];
            $appointmentData = [];

            if ($isAnnual) {
                for ($monthIndex = 0; $monthIndex < 12; $monthIndex++) {
                    $periodDate = $monthDate->copy()->month($monthIndex + 1);
                    $labels[] = $periodDate->translatedFormat('M Y');

                    $revenueData[] = Order::where('status', 'paid')
                        ->whereNotNull('paid_at')
                        ->whereBetween('paid_at', [$periodDate->copy()->startOfMonth(), $periodDate->copy()->endOfMonth()])
                        ->sum('total');

                    $patientData[] = Pasien::whereBetween('created_at', [$periodDate->copy()->startOfMonth(), $periodDate->copy()->endOfMonth()])->count();
                    $appointmentData[] = JanjiTemu::whereBetween('appointment_at', [$periodDate->copy()->startOfMonth(), $periodDate->copy()->endOfMonth()])->count();
                }
            } else {
                for ($dayIndex = 0; $dayIndex < $monthDate->daysInMonth; $dayIndex++) {
                    $periodDate = $monthDate->copy()->addDays($dayIndex);
                    $labels[] = $periodDate->format('d M');
                    $periodKey = $periodDate->format('Y-m-d');

                    $revenueData[] = Order::where('status', 'paid')
                        ->whereNotNull('paid_at')
                        ->whereDate('paid_at', $periodKey)
                        ->sum('total');

                    $patientData[] = Pasien::whereDate('created_at', $periodKey)->count();
                    $appointmentData[] = JanjiTemu::whereDate('appointment_at', $periodKey)->count();
                }
            }

            $revenueTotal = (int) Order::where('status', 'paid')
                ->whereNotNull('paid_at')
                ->whereBetween('paid_at', [$startDate, $endDate])
                ->sum('total');
            $metricTotal = match ($metric) {
                'patients' => $patientCount,
                'appointments' => $upcomingAppointmentCount,
                default => $revenueTotal,
            };

            $summaryText = match ($metric) {
                'patients' => 'Pasien ' . ($isAnnual ? 'tahun ini' : 'bulan ini') . ': ' . number_format($metricTotal, 0, ',', '.') . ' orang',
                'appointments' => 'Janji temu ' . ($isAnnual ? 'tahun ini' : 'bulan ini') . ': ' . number_format($metricTotal, 0, ',', '.') . ' agenda',
                default => 'Pemasukan ' . ($isAnnual ? 'tahun ini' : 'bulan ini') . ': Rp ' . number_format($metricTotal, 0, ',', '.'),
            };

            $paidPatients = Order::with('user.patient')
                ->where('status', 'paid')
                ->whereNotNull('paid_at')
                ->orderByDesc('paid_at')
                ->limit(4)
                ->get()
                ->map(function (Order $order): array {
                    $patient = $order->user?->patient;

                    return [
                        'name' => $patient?->name ?? $order->user?->name ?? 'Pasien tidak tersedia',
                        'amount' => (int) $order->total,
                        'paid_at' => $order->paid_at?->translatedFormat('d M, Y • H:i A') ?? '',
                    ];
                })
                ->values()
                ->all();

            $topProducts = OrderItem::query()
                ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->with('product')
                ->whereHas('order', function ($query): void {
                    $query->where('status', 'paid');
                })
                ->groupBy('product_id')
                ->orderByDesc('total_quantity')
                ->limit(6)
                ->get();

            $topProductMax = max($topProducts->max('total_quantity') ?? 0, 1);
            $topProductList = $topProducts->map(function (OrderItem $item) use ($topProductMax): array {
                $quantity = (int) ($item->total_quantity ?? 0);
                $product = $item->product;

                return [
                    'name' => $product?->title ?? 'Video tidak tersedia',
                    'quantity' => $quantity,
                    'width' => max(12, (int) round(($quantity / $topProductMax) * 100)),
                ];
            })->values()->all();

            $allSeries = [
                'labels' => $labels,
                'series' => [
                    ['name' => 'Pemasukan video', 'data' => $revenueData],
                    ['name' => 'Pasien', 'data' => $patientData],
                    ['name' => 'Janji temu', 'data' => $appointmentData],
                ],
            ];
            $metricIndex = ['income' => 0, 'patients' => 1, 'appointments' => 2][$metric];
            $chartData = [
                'labels' => $allSeries['labels'],
                'series' => [$allSeries['series'][$metricIndex]],
                'metric' => $metric,
            ];
        } catch (QueryException) {
            $patientCount = 0;
            $upcomingAppointmentCount = 0;
            $revenueTotal = 0;
            $metricTotal = 0;
            $chartData = ['labels' => [], 'series' => [
                ['name' => 'Pemasukan video', 'data' => []],
                ['name' => 'Pasien', 'data' => []],
                ['name' => 'Janji temu', 'data' => []],
            ]];
        }

        $viewData = [
            'patientCount' => $patientCount,
            'upcomingAppointmentCount' => $upcomingAppointmentCount,
            'revenueTotal' => $revenueTotal,
            'metricTotal' => $metricTotal,
            'period' => $period,
            'metric' => $metric,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'yearOptions' => $yearOptions,
            'chartData' => $chartData,
            'paidPatients' => $paidPatients ?? [],
            'topProductList' => $topProductList ?? [],
            'summaryText' => $summaryText ?? 'Data dashboard terbaru',
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'chartData' => $chartData,
                'metricTotal' => $metricTotal,
                'period' => $period,
                'metric' => $metric,
                'selectedMonth' => $selectedMonth,
            ]);
        }

        return view('welcome', $viewData);
    })->name('home');

    Route::get('/dashboard/export', function (Request $request) {
        $period = $request->query('period', 'month');
        $selectedMonth = $request->query('month', now()->format('Y-m'));
        $monthDate = preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $selectedMonth)
            ? Carbon::createFromFormat('Y-m-d', $selectedMonth.'-01')->startOfMonth()
            : now()->startOfMonth();
        $startDate = $period === 'year' ? now()->startOfYear() : $monthDate->copy()->startOfMonth();
        $endDate = $period === 'year' ? now()->endOfYear() : $monthDate->copy()->endOfMonth();

        $orders = Order::where('status', 'paid')->whereNotNull('paid_at')->whereBetween('paid_at', [$startDate, $endDate])->get();
        $patients = Pasien::whereBetween('created_at', [$startDate, $endDate])->get();
        $appointments = JanjiTemu::whereBetween('appointment_at', [$startDate, $endDate])->get();

        $csv = fopen('php://temp', 'r+');
        fputcsv($csv, ['Judul', 'Nilai']);
        fputcsv($csv, ['Periode', $period === 'year' ? 'Tahunan' : $monthDate->translatedFormat('F Y')]);
        fputcsv($csv, ['Total pemasukan video', $orders->sum('total')]);
        fputcsv($csv, ['Jumlah pasien baru', $patients->count()]);
        fputcsv($csv, ['Jumlah janji temu', $appointments->count()]);
        fputcsv($csv, ['Jumlah order selesai', $orders->count()]);
        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);

        return response($content)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="laporan_dashboard_'.now()->format('Ymd').'.xls"');
    })->name('dashboard.export');

    Route::get('/profile', function () {
        /** @var User|null $user */
        $user = Auth::user();

        abort_unless($user instanceof User, 403);

        $patient = $user->patient()->first()
            ?? Pasien::whereRaw('LOWER(email) = ?', [strtolower((string) $user->email)])->first()
            ?? Pasien::where('phone', $user->name)->first();

        abort_unless($patient, 404);

        return view('pasien.show', ['patient' => $patient->load('appointments')]);
    })->name('profile');

    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/store', [StoreController::class, 'index'])->name('store.index');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/my-videos/{product}', [VideoController::class, 'show'])->name('videos.show');
    Route::get('/my-videos/{product}/stream', [VideoController::class, 'stream'])->name('videos.stream');

    Route::patch('/appointments/{appointment}/status', [JanjiTemuController::class, 'updateStatus'])->name('appointments.status');
    Route::patch('/appointments/{appointment}/cancel', [JanjiTemuController::class, 'cancel'])->name('appointments.cancel');
    Route::resource('appointments', JanjiTemuController::class);
    Route::get('/catalog', [KatalogLayananController::class, 'index'])->name('catalog.index');

    Route::middleware('admin')->group(function (): void {
        Route::get('/admin/store', [AdminStoreController::class, 'index'])->name('admin.store');
        Route::get('/admin/payment-methods', [AdminStoreController::class, 'paymentMethods'])->name('admin.payment-methods');
        Route::get('/admin/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/admin/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::get('/admin/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::post('/admin/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        Route::resource('patients', PasienController::class);
        Route::resource('admin/clinic-services', LayananKlinikController::class)
            ->parameters(['clinic-services' => 'layananKlinik'])
            ->names('admin.clinic-services')
            ->only(['index', 'create', 'store', 'edit', 'update']);
        Route::patch('/admin/clinic-services/{layananKlinik}/toggle', [LayananKlinikController::class, 'toggle'])
            ->name('admin.clinic-services.toggle');
        Route::resource('products', ProductController::class);
        Route::resource('payment-methods', PaymentMethodController::class)->only(['store', 'update', 'destroy']);
        Route::patch('/orders/{order}/paid', [OrderController::class, 'markPaid'])->name('orders.paid');
        Route::patch('/orders/{order}/reject', [OrderController::class, 'markRejected'])->name('orders.reject');
    });
});
