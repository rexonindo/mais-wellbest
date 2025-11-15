<?php

namespace App\Filament\Pages;

use App\Filament\Resources\FBasePageResource;
use App\Models\ProductRoute;
use App\Models\Process;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Notifications\Notification;

// class ManageProductRoute extends Page implements Forms\Contracts\HasForms, Tables\Contracts\HasTable
class ManageProductRoute extends FBasePageResource implements HasTable
{
    use Forms\Concerns\InteractsWithForms;
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationGroup = 'Process & Flow';
    protected static ?string $navigationLabel = 'Manage Process Flow';
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $title = 'Manage Process Flow';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.pages.manage-product-route';

    public $itm_type;
    public $seq_no;
    public $proc_cd;

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('itm_type')
                ->label('P/N Type')
                ->required()
                ->maxLength(50),

            Forms\Components\TextInput::make('seq_no')
                ->label('Sequence No')
                ->numeric()
                ->required(),

            Forms\Components\Select::make('proc_cd')
                ->label('Process')
                ->options(Process::pluck('proc_nm', 'proc_cd'))
                ->required()
                ->searchable(),
        ];
    }

    public function save(): void
    {
        $validated = $this->form->getState();

        ProductRoute::create($validated);

        $this->form->fill(); // reset form
        $this->dispatch('refresh-table');

        Notification::make()
            ->title('Process Flow created successfully.')
            ->success()
            ->send();
    }

    protected function getTableQuery()
    {
        return ProductRoute::query()->with('process');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('itm_type')->label('P/N Type')->searchable(),
            Tables\Columns\TextColumn::make('seq_no')->label('Seq No')->searchable(),
            Tables\Columns\TextColumn::make('process.proc_nm')
                ->label('Process')
                ->formatStateUsing(function ($state, $record) {
                    // Ensure relation exists
                    if (!$record->process) {
                        return null;
                    }

                    $procName = $record->process->proc_nm ?? '';
                    $procId = $record->process->proc_id ?? $record->process->proc_cd ?? $record->process->id ?? '';

                    return $procId ? "{$procName} ({$procId})" : $procName;
                })
                ->searchable(),


        ];
    }
}
