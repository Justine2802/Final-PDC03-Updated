<?php

namespace App\Livewire\Admin;

use App\Models\Inquiry;
use Livewire\Component;
use Livewire\WithPagination;

class Inquiries extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';
    public int $perPage = 10;

    public ?int $viewingId = null;
    public string $responseText = '';

    public function updatingSearch(): void     { $this->resetPage(); }
    public function updatedPerPage(): void     { $this->resetPage(); }
    public function updatedFilterStatus(): void { $this->resetPage(); }

    public function viewInquiry(int $id): void
    {
        $this->viewingId = $id;
        $inquiry = Inquiry::find($id);
        $this->responseText = $inquiry?->response ?? '';
    }

    public function closeView(): void
    {
        $this->viewingId     = null;
        $this->responseText  = '';
    }

    public function submitResponse(): void
    {
        $this->validate(['responseText' => 'required|min:5|max:2000']);

        $inquiry = Inquiry::find($this->viewingId);
        if (!$inquiry) return;

        $inquiry->update([
            'response'     => $this->responseText,
            'status'       => 'responded',
            'responded_at' => now(),
        ]);

        $this->dispatch('notify', message: 'Response sent successfully.');
        $this->closeView();
    }

    public function markClosed(int $id): void
    {
        Inquiry::find($id)?->update(['status' => 'closed']);
        $this->dispatch('notify', message: 'Inquiry closed.');
    }

    public function render()
    {
        $inquiries = Inquiry::with(['property', 'user'])
            ->when($this->search, function ($q) {
                $q->where('message', 'like', "%{$this->search}%")
                  ->orWhereHas('user',     fn ($uq) => $uq->where('name',  'like', "%{$this->search}%"))
                  ->orWhereHas('property', fn ($pq) => $pq->where('title', 'like', "%{$this->search}%"));
            })
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate($this->perPage);

        $viewing = $this->viewingId
            ? Inquiry::with(['property', 'user'])->find($this->viewingId)
            : null;

        return view('livewire.admin.inquiries', compact('inquiries', 'viewing'))
            ->layout('components.layouts.admin')
            ->title('Inquiries');
    }
}
