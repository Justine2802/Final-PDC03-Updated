<?php

namespace App\Livewire\Admin;

use App\Models\Inquiry;
use App\Models\Property;
use App\Models\Reservation;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public int $totalProperties = 0;
    public int $totalUsers = 0;
    public int $totalReservations = 0;
    public int $pendingInquiries = 0;

    public function mount(): void
    {
        $this->totalProperties = Property::count();
        $this->totalUsers = User::where('role', 'renter')->count();
        $this->totalReservations = Reservation::count();
        $this->pendingInquiries = Inquiry::where('status', 'pending')->count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('components.layouts.admin')
            ->title('Dashboard');
    }
}
