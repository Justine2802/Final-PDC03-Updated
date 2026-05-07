<?php

namespace App\Livewire\Admin;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Reviews extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterRating   = '';
    public string $filterVerified = '';
    public string $dateFrom = '';
    public string $dateTo   = '';
    public int    $perPage  = 10;

    public ?int $viewingId = null;

    public function updatingSearch(): void        { $this->resetPage(); }
    public function updatedPerPage(): void         { $this->resetPage(); }
    public function updatedFilterRating(): void    { $this->resetPage(); }
    public function updatedFilterVerified(): void  { $this->resetPage(); }
    public function updatedDateFrom(): void        { $this->resetPage(); }
    public function updatedDateTo(): void          { $this->resetPage(); }

    public function viewReview(int $id): void  { $this->viewingId = $id; }
    public function closeView(): void           { $this->viewingId = null; }

    public function toggleVerified(int $id): void
    {
        $review = Review::find($id);
        if ($review) {
            $review->update(['is_verified' => !$review->is_verified]);
            $this->dispatch('notify', message: 'Review verification toggled.');
        }
        if ($this->viewingId === $id) $this->viewingId = null;
    }

    public function deleteReview(int $id): void
    {
        Review::find($id)?->delete();
        if ($this->viewingId === $id) $this->viewingId = null;
        $this->dispatch('notify', message: 'Review deleted.');
    }

    public function export(): StreamedResponse
    {
        $rows = $this->buildQuery()->get();
        return response()->streamDownload(function () use ($rows) {
            $h = fopen('php://output', 'w');
            fputcsv($h, ['ID', 'Property', 'Renter', 'Rating', 'Comment', 'Pros', 'Cons', 'Verified', 'Date']);
            foreach ($rows as $r) {
                fputcsv($h, [
                    $r->id,
                    $r->property->title ?? '',
                    $r->renter->name ?? '',
                    $r->rating,
                    $r->comment ?? '',
                    is_array($r->pros) ? implode(', ', $r->pros) : ($r->pros ?? ''),
                    is_array($r->cons) ? implode(', ', $r->cons) : ($r->cons ?? ''),
                    $r->is_verified ? 'Verified' : 'Unverified',
                    $r->created_at->format('Y-m-d'),
                ]);
            }
            fclose($h);
        }, 'reviews-' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
    }

    public function exportExcel(): StreamedResponse
    {
        $rows = $this->buildQuery()->get();
        return response()->streamDownload(function () use ($rows) {
            $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' . "\n";
            $xml .= '<Worksheet ss:Name="Reviews"><Table>' . "\n";
            $xml .= '<Row>' . implode('', array_map(fn($h) => '<Cell><Data ss:Type="String">' . htmlspecialchars($h, ENT_XML1) . '</Data></Cell>',
                ['ID', 'Property', 'Renter', 'Rating', 'Comment', 'Pros', 'Cons', 'Verified', 'Date'])) . '</Row>' . "\n";
            foreach ($rows as $r) {
                $xml .= '<Row><Cell><Data ss:Type="Number">' . $r->id . '</Data></Cell>';
                foreach ([
                    $r->property->title ?? '', $r->renter->name ?? '', $r->rating,
                    $r->comment ?? '',
                    is_array($r->pros) ? implode(', ', $r->pros) : ($r->pros ?? ''),
                    is_array($r->cons) ? implode(', ', $r->cons) : ($r->cons ?? ''),
                    $r->is_verified ? 'Verified' : 'Unverified',
                    $r->created_at->format('Y-m-d'),
                ] as $v) {
                    $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars((string) $v, ENT_XML1) . '</Data></Cell>';
                }
                $xml .= '</Row>' . "\n";
            }
            $xml .= '</Table></Worksheet></Workbook>';
            echo $xml;
        }, 'reviews-' . now()->format('Y-m-d') . '.xls', ['Content-Type' => 'application/vnd.ms-excel']);
    }

    private function buildQuery()
    {
        return Review::with(['property', 'property.images', 'renter', 'reservation'])
            ->when($this->search, fn ($q) =>
                $q->whereHas('property', fn ($pq) => $pq->where('title', 'like', "%{$this->search}%"))
                  ->orWhereHas('renter', fn ($uq) => $uq->where('name', 'like', "%{$this->search}%"))
                  ->orWhere('comment', 'like', "%{$this->search}%"))
            ->when($this->filterRating, fn ($q) => $q->where('rating', $this->filterRating))
            ->when($this->filterVerified !== '', fn ($q) => $q->where('is_verified', (bool) $this->filterVerified))
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->latest();
    }

    public function render()
    {
        $reviews = $this->buildQuery()->paginate($this->perPage);

        $activeFilterCount = collect([$this->filterRating, $this->filterVerified, $this->dateFrom, $this->dateTo])
            ->filter(fn ($v) => $v !== '')->count();

        $viewing = $this->viewingId
            ? Review::with(['property', 'property.images', 'renter', 'reservation'])->find($this->viewingId)
            : null;

        return view('livewire.admin.reviews', compact('reviews', 'viewing', 'activeFilterCount'))
            ->layout('components.layouts.admin')
            ->title('Reviews');
    }
}
