<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cabinet;

class Doctors extends Component
{
    use WithPagination;

    public $search = '';

    // Debounce the search to avoid too many requests
    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSearch($value)
    {
        // Optional: Add any additional logic when search is updated
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = Cabinet::with(['doctor:id,name,email','doctor.media']);

        if ($this->search) {
            $query->whereHas('doctor', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.doctors', [
            'cabinets' => $query->paginate(10)
        ]);
    }
}
