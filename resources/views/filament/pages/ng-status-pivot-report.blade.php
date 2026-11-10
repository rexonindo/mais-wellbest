<x-filament-panels::page>
    <div style="position: relative; z-index: 5;">
        {{ $this->form }}
    </div>

    <div style="margin-top: 10px;">
        {{ $this->table }}
    </div>

    <style>
        .fi-ta-content {
            max-height: calc(100vh - 250px);
            overflow-y: auto;
        }

        .fi-ta-table thead th {
            position: sticky;
            top: 0;
            z-index: 1;
            background: white;
        }
    </style>
</x-filament-panels::page>
