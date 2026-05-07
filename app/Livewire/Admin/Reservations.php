<?php

namespace App\Livewire\Admin;

use App\Models\Reservation;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Reservations extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';
    public string $dateFrom = '';
    public string $dateTo   = '';
    public int    $perPage  = 10;

    public ?int $viewingId = null;
    public ?int $confirmingId = null;
    public string $confirmingAction = '';

    public function updatingSearch(): void       { $this->resetPage(); }
    public function updatedPerPage(): void        { $this->resetPage(); }
    public function updatedFilterStatus(): void   { $this->resetPage(); }
    public function updatedDateFrom(): void       { $this->resetPage(); }
    public function updatedDateTo(): void         { $this->resetPage(); }

    public function viewReservation(int $id): void { $this->viewingId = $id; }
    public function closeView(): void              { $this->viewingId = null; }

    public function askConfirm(int $id, string $action): void
    {
        $this->confirmingId     = $id;
        $this->confirmingAction = $action;
    }

    public function dismissConfirm(): void
    {
        $this->confirmingId     = null;
        $this->confirmingAction = '';
    }

    public function executeAction(): void
    {
        $reservation = Reservation::find($this->confirmingId);
        if (!$reservation) { $this->dismissConfirm(); return; }

        match ($this->confirmingAction) {
            'confirm'  => $reservation->update(['status' => 'confirmed',  'confirmed_at' => now()]),
            'cancel'   => $reservation->update(['status' => 'cancelled',  'cancelled_at' => now()]),
            'complete' => $reservation->update(['status' => 'completed']),
            default    => null,
        };

        $this->dismissConfirm();
        if ($this->viewingId === $reservation->id) $this->viewingId = null;
        $this->dispatch('notify', message: 'Reservation updated successfully.');
    }

    public function export(): StreamedResponse
    {
        $rows = $this->buildQuery()->get();
        return response()->streamDownload(function () use ($rows) {
            $h = fopen('php://output', 'w');
            fputcsv($h, ['ID', 'Property', 'Renter', 'Email', 'Move In', 'Move Out', 'Total', 'Status', 'Created']);
            foreach ($rows as $r) {
                fputcsv($h, [
                    $r->id, $r->property->title ?? '', $r->user->name ?? '', $r->user->email ?? '',
                    $r->move_in_date->format('Y-m-d'), $r->move_out_date?->format('Y-m-d') ?? '',
                    $r->total_price, ucfirst($r->status), $r->created_at->format('Y-m-d'),
                ]);
            }
            fclose($h);
        }, 'reservations-' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
    }

    public function exportExcel(): StreamedResponse
    {
        $rows = $this->buildQuery()->get();
        return response()->streamDownload(function () use ($rows) {
            $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' . "\n";
            $xml .= '<Worksheet ss:Name="Reservations"><Table>' . "\n";
            $xml .= '<Row>' . implode('', array_map(fn($h) => '<Cell><Data ss:Type="String">' . htmlspecialchars($h, ENT_XML1) . '</Data></Cell>',
                ['ID', 'Property', 'Renter', 'Email', 'Move In', 'Move Out', 'Total', 'Status', 'Created'])) . '</Row>' . "\n";
            foreach ($rows as $r) {
                $xml .= '<Row><Cell><Data ss:Type="Number">' . $r->id . '</Data></Cell>';
                foreach ([
                    $r->property->title ?? '', $r->user->name ?? '', $r->user->email ?? '',
                    $r->move_in_date->format('Y-m-d'), $r->move_out_date?->format('Y-m-d') ?? '',
                    $r->total_price, ucfirst($r->status), $r->created_at->format('Y-m-d'),
                ] as $v) {
                    $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars((string) $v, ENT_XML1) . '</Data></Cell>';
                }
                $xml .= '</Row>' . "\n";
            }
            $xml .= '</Table></Worksheet></Workbook>';
            echo $xml;
        }, 'reservations-' . now()->format('Y-m-d') . '.xls', ['Content-Type' => 'application/vnd.ms-excel']);
    }

    private function buildQuery()
    {
        return Reservation::with(['property', 'property.images', 'property.propertyType', 'user'])
            ->when($this->search, fn ($q) =>
                $q->whereHas('property', fn ($pq) => $pq->where('title', 'like', "%{$this->search}%"))
                  ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$this->search}%")))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->latest();
    }

    public function render()
    {
        $reservations = $this->buildQuery()->paginate($this->perPage);

        $activeFilterCount = collect([$this->filterStatus, $this->dateFrom, $this->dateTo])
            ->filter(fn ($v) => $v !== '')->count();

        $viewing = $this->viewingId
            ? Reservation::with([
                'property', 'property.images', 'property.propertyType',
                'property.address.barangay.city.province', 'user',
              ])->find($this->viewingId)
            : null;

        return view('livewire.admin.reservations', compact('reservations', 'viewing', 'activeFilterCount'))
            ->layout('components.layouts.admin')
            ->title('Reservations');
    }
}
