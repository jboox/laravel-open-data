<?php

namespace App\Livewire;

use Livewire\Component;

class TestSelect extends Component
{
    public array $selected = [];

    public function render()
    {
        return view('livewire.test-select')
            ->layout('layouts.app');
    }
}
