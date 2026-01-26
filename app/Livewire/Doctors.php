<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cabinet;

class Doctors extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.doctors', [
            'cabinets' => Cabinet::with(['doctor:id,name,email','doctor.media'])->paginate(10)
        ]);
    }
}
