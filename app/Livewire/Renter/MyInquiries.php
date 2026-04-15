<?php

namespace App\Livewire\Renter;

use App\Models\Inquiry;
use Livewire\Component;
use Livewire\WithPagination;

class MyInquiries extends Component
{
    use WithPagination;

    public string $statusFilter = '';

    protected $queryString = ['statusFilter' => ['except' => '']];

    public function render()
    {
        $query = Inquiry::where('user_id', auth()->id());

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $inquiries = $query->with(['property', 'property.propertyType'])
            ->latest()
            ->paginate(10);

        return view('livewire.renter.my-inquiries', [
            'inquiries' => $inquiries,
        ])->layout('components.layouts.renter')->title('My Inquiries');
    }
}
