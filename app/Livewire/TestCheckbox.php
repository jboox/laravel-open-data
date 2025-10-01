<?php

namespace App\Livewire;

use Livewire\Component;

class TestCheckbox extends Component
{
    public array $selected = [];

    public function render()
    {
        return view('livewire.test-checkbox')
            ->layout('layouts.app'); // pakai layouts/app.blade.php
    }
}