<?php

namespace App\Http\Livewire;

use Filament\Notifications\Notification;
use Livewire\Component;

class CustomSelectModal extends Component
{   
    public function openModal()
    {
        // Emitir un evento para abrir el modal
        $this->dispatchBrowserEvent('open-modal', ['id' => 'my-custom-modal']);

        Notification::make()
        ->title('Modal abierto!')
        ->success()
        ->send();
    }

    public function closeModal()
    {
        // Emitir un evento para cerrar el modal
        $this->dispatchBrowserEvent('close-modal', ['id' => 'my-custom-modal']);
    }

    public function render()
    {
        return view('livewire.custom-select-modal');
    }
}
