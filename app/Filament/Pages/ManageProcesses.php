<?php

namespace App\Filament\Pages;

use App\Filament\FilamentBasePage;
use App\Models\Process;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Notifications\Notification;

//class ManageProcesses extends Page implements Forms\Contracts\HasForms, Tables\Contracts\HasTable
class ManageProcesses extends FilamentBasePage implements HasTable
{
    use Forms\Concerns\InteractsWithForms;
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationGroup = 'Process & Flow';
    protected static ?string $navigationLabel = 'Manage Process';
    protected static ?string $navigationIcon = 'heroicon-o-cog';    
    protected static ?string $title = 'Manage Process';       
    protected static ?int $navigationSort = 2;          
    protected static string $view = 'filament.pages.manage-processes';

    public $proc_cd;
    public $proc_nm;
    public $dept_cd;
    public $std_time;

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getTableRecordFormLayout(): ?string
    {
        return 'vertical'; // shows table above form
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('proc_cd')
                ->label('Process Code')
                ->required()
                ->maxLength(50),

            Forms\Components\TextInput::make('proc_nm')
                ->label('Process Name')
                ->required()
                ->maxLength(100),

            Forms\Components\Select::make('dept_cd')
                ->label('Department')
                ->options(Department::pluck('dept_nm', 'dept_cd')->toArray())
                ->required()
                ->searchable(),

            Forms\Components\TextInput::make('std_time')
                ->label('Standard Time')
                ->numeric()
                ->suffix('min'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        Process::create($data);
        $this->form->fill(); // reset form
        $this->dispatch('refresh-table'); // refresh table below
        // $this->notify('success', 'Process created successfully.');
        Notification::make()
            ->title('Process created successfully.')
            ->success()
            ->send();
    }

    protected function getTableQuery()
    {
        return Process::query()->with('department');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('proc_cd')->label('Code')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('proc_nm')->label('Name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('department.dept_nm')->label('Department'),
            Tables\Columns\TextColumn::make('std_time')->label('Std Time'),
        ];
    }
}
