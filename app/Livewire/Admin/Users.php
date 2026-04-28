<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Users extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterRole = '';
    public string $filterStatus = '';
    public string $sortBy = 'created_at';
    public string $sortDir = 'desc';
    public string $dateFrom = '';
    public string $dateTo = '';
    public int $perPage = 10;

    // View modal
    public ?int $viewingId = null;

    // Edit modal
    public ?int $editingId    = null;
    public string $editName   = '';
    public string $editEmail  = '';
    public string $editPhone  = '';
    public string $editRole   = '';
    public string $editStatus = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterRole(): void
    {
        $this->resetPage();
    }

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatedDateTo(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'filterRole', 'filterStatus', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    // ── View ──────────────────────────────────────────────
    public function viewUser(int $id): void
    {
        $this->viewingId = $id;
    }

    public function closeView(): void
    {
        $this->viewingId = null;
    }

    // ── Edit ──────────────────────────────────────────────
    public function openEdit(int $id): void
    {
        $user = User::find($id);
        if (!$user) return;

        $this->viewingId    = null; // close view modal if open
        $this->editingId    = $id;
        $this->editName     = $user->name;
        $this->editEmail    = $user->email;
        $this->editPhone    = $user->phone ?? '';
        $this->editRole     = $user->role;
        $this->editStatus   = $user->status;
        $this->resetValidation();
    }

    public function closeEdit(): void
    {
        $this->editingId = null;
        $this->resetValidation();
    }

    public function saveEdit(): void
    {
        $this->validate([
            'editName'   => 'required|string|min:2|max:255',
            'editEmail'  => 'required|email|unique:users,email,' . $this->editingId,
            'editPhone'  => 'nullable|string|max:20',
            'editRole'   => 'required|in:admin,renter',
            'editStatus' => 'required|in:active,inactive,suspended',
        ]);

        User::find($this->editingId)?->update([
            'name'   => $this->editName,
            'email'  => $this->editEmail,
            'phone'  => $this->editPhone ?: null,
            'role'   => $this->editRole,
            'status' => $this->editStatus,
        ]);

        $this->dispatch('notify', message: 'User updated successfully.');
        $this->closeEdit();
    }

    public function updateStatus(int $id, string $status): void
    {
        User::find($id)?->update(['status' => $status]);
        $this->dispatch('notify', message: 'User status updated.');
    }

    public function export(): StreamedResponse
    {
        $users = $this->buildQuery()->get();

        return response()->streamDownload(function () use ($users) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Email', 'Role', 'Status', 'Phone', 'Joined']);

            foreach ($users as $user) {
                fputcsv($handle, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->status,
                    $user->phone,
                    $user->created_at->format('Y-m-d'),
                ]);
            }

            fclose($handle);
        }, 'users-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportExcel(): StreamedResponse
    {
        $users = $this->buildQuery()->get();

        return response()->streamDownload(function () use ($users) {
            $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
            $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"';
            $xml .= ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' . "\n";
            $xml .= '<Worksheet ss:Name="Users"><Table>' . "\n";

            // Header row
            $xml .= '<Row>';
            foreach (['ID', 'Name', 'Email', 'Role', 'Status', 'Phone', 'Joined'] as $header) {
                $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($header, ENT_XML1) . '</Data></Cell>';
            }
            $xml .= '</Row>' . "\n";

            // Data rows
            foreach ($users as $user) {
                $xml .= '<Row>';
                $xml .= '<Cell><Data ss:Type="Number">' . $user->id . '</Data></Cell>';
                foreach ([$user->name, $user->email, $user->role, $user->status, $user->phone ?? '', $user->created_at->format('Y-m-d')] as $value) {
                    $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars((string) $value, ENT_XML1) . '</Data></Cell>';
                }
                $xml .= '</Row>' . "\n";
            }

            $xml .= '</Table></Worksheet></Workbook>';
            echo $xml;
        }, 'users-' . now()->format('Y-m-d') . '.xls', [
            'Content-Type' => 'application/vnd.ms-excel',
        ]);
    }

    private function buildQuery()
    {
        return User::query()
            ->when($this->search, fn ($q) => $q->where(fn ($sq) => $sq
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")))
            ->when($this->filterRole, fn ($q) => $q->where('role', $this->filterRole))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->orderBy($this->sortBy, $this->sortDir);
    }

    public function render()
    {
        $users = $this->buildQuery()->paginate($this->perPage);

        $activeFilterCount = collect([$this->filterRole, $this->filterStatus, $this->dateFrom, $this->dateTo])
            ->filter()
            ->count();

        $viewing = $this->viewingId
            ? User::find($this->viewingId)
            : null;

        return view('livewire.admin.users', compact('users', 'activeFilterCount', 'viewing'))
            ->layout('components.layouts.admin')
            ->title('Users');
    }
}
