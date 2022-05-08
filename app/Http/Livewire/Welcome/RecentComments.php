<?php

namespace App\Http\Livewire\Welcome;

use Filament\Tables;
use App\Models\Comment;
use Livewire\Component;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Concerns\InteractsWithTable;

class RecentComments extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery(): Builder
    {
        return Comment::query()->limit(10)->latest();
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('content'),
            Tables\Columns\TextColumn::make('item.title'),
        ];
    }

    public function render()
    {
        return view('livewire.welcome.recent-comments');
    }
}
