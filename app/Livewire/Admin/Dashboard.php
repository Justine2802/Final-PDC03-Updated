<?php

namespace App\Livewire\Admin;

use App\Models\Inquiry;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public int $totalProperties = 0;
    public int $totalUsers = 0;
    public int $totalReservations = 0;
    public int $pendingInquiries = 0;

    public string $reservationRange = '30days';
    public string $userRange        = '30days';

    public array $reservationsChartData = ['labels' => [], 'data' => []];
    public array $usersChartData        = ['labels' => [], 'data' => []];

    public function mount(): void
    {
        $this->totalProperties   = Property::count();
        $this->totalUsers        = User::where('role', 'renter')->count();
        $this->totalReservations = Reservation::count();
        $this->pendingInquiries  = Inquiry::where('status', 'pending')->count();

        $this->reservationsChartData = $this->timeSeries('reservations', $this->reservationRange);
        $this->usersChartData        = $this->timeSeries('users', $this->userRange, ['role' => 'renter']);
    }

    private function timeSeries(string $table, string $range, array $where = []): array
    {
        [$start, $format, $labels, $keys] = match ($range) {
            '7days' => [
                now()->subDays(6)->startOfDay(),
                '%Y-%m-%d',
                collect(range(0, 6))->map(fn ($i) => now()->subDays(6 - $i)->format('M d'))->toArray(),
                collect(range(0, 6))->map(fn ($i) => now()->subDays(6 - $i)->format('Y-m-d'))->toArray(),
            ],
            '6months' => [
                now()->subMonths(5)->startOfMonth(),
                '%Y-%m',
                collect(range(0, 5))->map(fn ($i) => now()->subMonths(5 - $i)->format('M Y'))->toArray(),
                collect(range(0, 5))->map(fn ($i) => now()->subMonths(5 - $i)->format('Y-m'))->toArray(),
            ],
            default => [ // 30days
                now()->subDays(29)->startOfDay(),
                '%Y-%m-%d',
                collect(range(0, 29))->map(fn ($i) => now()->subDays(29 - $i)->format('M d'))->toArray(),
                collect(range(0, 29))->map(fn ($i) => now()->subDays(29 - $i)->format('Y-m-d'))->toArray(),
            ],
        };

        $query = DB::table($table)->where('created_at', '>=', $start);
        foreach ($where as $col => $val) {
            $query->where($col, $val);
        }

        $rows = $query
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as period, count(*) as count")
            ->groupBy('period')
            ->pluck('count', 'period')
            ->toArray();

        return [
            'labels' => $labels,
            'data'   => array_map(fn ($k) => $rows[$k] ?? 0, $keys),
        ];
    }

    public function updatedReservationRange(): void
    {
        $this->reservationsChartData = $this->timeSeries('reservations', $this->reservationRange);
    }

    public function updatedUserRange(): void
    {
        $this->usersChartData = $this->timeSeries('users', $this->userRange, ['role' => 'renter']);
    }

    public function render()
    {
        // Chart 1 — Reservations over time
        $reservationsOverTime = $this->timeSeries('reservations', $this->reservationRange);

        // Chart 2 — Reservation status breakdown
        $reservationStatuses = Reservation::selectRaw('status, count(*) as cnt')
            ->groupBy('status')->pluck('cnt', 'status')->toArray();

        // Chart 3 — Properties by type
        $propertiesByType = PropertyType::withCount('properties')
            ->get()->pluck('properties_count', 'name')->toArray();

        // Chart 4 — Top cities by listing count
        $propertiesByCity = DB::table('addresses')
            ->join('barangays', 'addresses.barangay_id', '=', 'barangays.id')
            ->join('cities', 'barangays.city_id', '=', 'cities.id')
            ->selectRaw('cities.name as city_name, count(*) as cnt')
            ->groupBy('cities.id', 'cities.name')
            ->orderByDesc('cnt')
            ->limit(10)
            ->pluck('cnt', 'city_name')
            ->toArray();

        // Chart 5 — User registrations over time
        $usersOverTime = $this->timeSeries('users', $this->userRange, ['role' => 'renter']);

        // Chart 6 — User verification status
        $verificationStatus = User::where('role', 'renter')
            ->selectRaw('IFNULL(id_verification_status, "none") as status, count(*) as cnt')
            ->groupBy('id_verification_status')
            ->pluck('cnt', 'status')
            ->toArray();

        // Chart 7 — Review ratings distribution
        $reviewRatings = Review::selectRaw('rating, count(*) as cnt')
            ->groupBy('rating')->orderBy('rating')
            ->pluck('cnt', 'rating')->toArray();
        $ratingsData = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingsData[$i] = $reviewRatings[$i] ?? 0;
        }

        return view('livewire.admin.dashboard', compact(
            'reservationsOverTime',
            'reservationStatuses',
            'propertiesByType',
            'propertiesByCity',
            'usersOverTime',
            'verificationStatus',
            'ratingsData',
        ))->layout('components.layouts.admin')->title('Dashboard');
    }
}
