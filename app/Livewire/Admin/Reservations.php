<?php

namespace App\Livewire\Admin;

use App\Models\Reservation;
use Livewire\Component;
use Livewire\WithPagination;

class Reservations extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';
    public int $perPage = 10;

    public ?int $viewingId = null;
    public ?int $confirmingId = null;
    public string $confirmingAction = ''; // 'confirm' | 'cancel' | 'complete'

    public function updatingSearch(): void  { $this->resetPage(); }
    public function updatedPerPage(): void  { $this->resetPage(); }
    public function updatedFilterStatus(): void { $this->resetPage(); }

    public function viewReservation(int $id): void
    {
        $this->viewingId = $id;
    }

    public function closeView(): void
    {
        $this->viewingId = null;
    }

    public function askConfirm(int $id, string $action): void
    {
        $this->confirmingId = $id;
        $this->confirmingAction = $action;
    }

    public function dismissConfirm(): void
    {
        $this->confirmingId = null;
        $this->confirmingAction = '';
    }

    public function executeAction(): void
    {
        $reservation = Reservation::find($this->confirmingId);
        if (!$reservation) {
            $this->dismissConfirm();
            return;
        }

        match ($this->confirmingAction) {
            'confirm'  => $reservation->update(['status' => 'confirmed',  'confirmed_at' => now()]),
            'cancel'   => $reservation->update(['status' => 'cancelled',  'cancelled_at' => now()]),
            'complete' => $reservation->update(['status' => 'completed']),
            default    => null,
        };

        $this->dismissConfirm();

        // Refresh viewing modal if open
        if ($this->viewingId === $reservation->id) {
            $this->viewingId = null;
        }

        $this->dispatch('notify', message: 'Reservation updated successfully.');
    }

    public function render()
    {
        $reservations = Reservation::with(['property', 'property.propertyType', 'user'])
            ->when($this->search, function ($q) {
                $q->whereHas('property', fn ($pq) => $pq->where('title', 'like', "%{$this->search}%"))
                  ->orWhereHas('user',     fn ($uq) => $uq->where('name',  'like', "%{$this->search}%"));
            })
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate($this->perPage);

        $viewing = $this->viewingId
            ? Reservation::with(['property', 'property.propertyType', 'property.address', 'user'])->find($this->viewingId)
            : null;

        return view('livewire.admin.reservations', compact('reservations', 'viewing'))
            ->layout('components.layouts.admin')
            ->title('Reservations');
    }
}
