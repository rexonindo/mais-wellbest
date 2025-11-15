<?php

namespace App\Filament\Resources\WorkOrderResource\Pages;

use App\Filament\Resources\WorkOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Components\TextEntry;

class EditWorkOrder extends EditRecord
{
    protected static string $resource = WorkOrderResource::class;
    protected static ?string $title = 'Work Order <Edit>';    

    protected function afterSave(): void
    {
        // Refresh from DB to get trigger-updated value
        $this->record->refresh();

        // Refill the Filament form with latest data
        if (method_exists($this, 'form') && $this->form) {
            $this->form->fill($this->record->toArray());
        }

        // Optional diagnostic log
        $dbValue = DB::table($this->record->getTable())
            ->where('id', $this->record->id)
            ->value('plan_qty_pnl');
/*
        Log::info('WorkOrder afterSave refresh', [
            'id' => $this->record->id,
            'model_value' => $this->record->plan_qty_pnl,
            'db_value' => $dbValue,
        ]);

        // ✅ Filament v3 uses Notification class
        Notification::make()
            ->title('Record refreshed from database')
            ->success()
            ->send();
*/            
    }

    protected function getHeaderActions(): array
    {
        return [


            Actions\Action::make('generateProcess')
                ->label('Generate Process')
                ->icon('heroicon-o-cog')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    $woNo = $this->record->wo_no;
                    $user = Auth::user()->name ?? 'system';

                    try {
                        DB::statement('CALL gen_wo_process(?, ?)', [$woNo, $user]);

                        Notification::make()
                            ->title('Process Generated')
                            ->body("Stored procedure executed for WO: {$woNo}")
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Error Running Procedure')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Actions\Action::make('showProcess')
                ->label('Show Process')
                ->icon('heroicon-o-eye')
                ->modalHeading('Work Order Process')
                ->modalWidth('3xl')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close')
                ->modalContent(function () {
                    $woNo = $this->record->wo_no;

                    $rows = DB::table('wo_proc_tbl')
                        ->select('seq_no', 'proc_cd', 'cav', 'shoot_qty')
                        ->where('wo_no', $woNo)
                        ->orderBy('seq_no')
                        ->get();

                    if ($rows->isEmpty()) {
                        return view('filament.components.no-data', [
                            'message' => "No process data found for WO: {$woNo}",
                        ]);
                    }

                    return new \Illuminate\Support\HtmlString('
                        <div class="max-h-[60vh] overflow-y-auto p-2">
                            ' . view('filament.components.show-process-table', ['rows' => $rows])->render() . '
                        </div>
                    ');
  
                }),


            Actions\DeleteAction::make(),

        ];
    }    

}
