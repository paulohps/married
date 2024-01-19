<?php

namespace App\Livewire\Site;

use Livewire\Component;
use Filament\Forms\Form;
use Filament\Support\RawJs;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\{Repeater, TextInput};

class Invite extends Component implements HasForms
{
    use InteractsWithForms;

    public bool $showModal = false;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function render(): View
    {
        return view('livewire.site.invite')->layout('components.layouts.site');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('presences')
                    ->label('Pessoas')
                    ->columns(2)
                    ->addActionLabel('Adicionar pessoa')
                    ->orderColumn(false)
                    ->addable(fn() => !$this->hasEmptyPresence())
                    ->live()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->live()
                            ->required(),
                        TextInput::make('phone')
                            ->label('Whatsapp')
                            ->mask(RawJs::make(
                                <<<'JS'
                                    $input.length >= 14 ? '(99) 99999-9999' : '(99) 9999-9999'
                                JS
                            ))
                    ])
            ])
            ->statePath('data');
    }

    public function save(): void
    {

    }

    public function updatedShowModal(): void
    {
        if ($this->showModal) {
            return;
        }

        $this->form->fill();
    }

    private function hasEmptyPresence(): bool
    {
        return collect($this->data['presences'] ?? [])
            ->contains(fn($presence) => empty($presence['name']));
    }
}
