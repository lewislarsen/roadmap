<?php

namespace App\Http\Livewire\Item;

use function auth;
use function view;
use App\Models\Item;
use App\Models\Board;
use function redirect;
use App\Models\Project;
use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Http\Livewire\Concerns\CanNotify;
use Filament\Forms\Concerns\InteractsWithForms;

class Create extends Component implements HasForms
{
    use InteractsWithForms, CanNotify;

    public Project|null $project = null;
    public Board|null $board = null;

    public function mount()
    {
        $this->form->fill([]);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->label(trans('table.title'))
                ->minLength(3)
                ->required(),
            MarkdownEditor::make('content')
                ->label(trans('table.content'))
                ->minLength(10)
                ->required(),
        ];
    }

    public function submit()
    {
        $formState = $this->form->getState();

        $item = Item::create([
            ...$formState,
        ]);

        $item->user()->associate(auth()->user())->save();
        $item->board()->associate($this->board)->save();

        $item->toggleUpvote();

        $this->notify('success', trans('items.item_created'));

        if (!$this->project) {
            return redirect()->route('home');
        }

        return redirect()->route('projects.boards.show', [$this->project->id, $this->board->id]);
    }

    public function render()
    {
        return view('livewire.item.create');
    }
}
