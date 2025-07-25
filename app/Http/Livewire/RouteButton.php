<?php

namespace App\Http\Livewire;

use Livewire\Component;

class RouteButton extends Component
{
    // Esta propiedad contendrá el texto que se mostrará en el botón.
    public $buttonText = 'Ruta';

    // Escucha el evento 'optionSelectedInModal' que será emitido por ModalWithSelect
    // Cuando este evento se recibe, llama al método 'updateButtonText'.
    protected $listeners = ['optionSelectedInModal' => 'updateButtonText'];

    // Este método actualiza el texto del botón.
    public function updateButtonText($newLabel)
    {
        $this->buttonText = $newLabel;
    }

    public function render()
    {
        return view('livewire.route-button');
    }
}
