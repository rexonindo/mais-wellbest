
<x-filament-panels::page>
    <div class="ng-report-layout">

        {{-- REPORT TYPE --}}
        <div class="ng-report-type">
            <label for="reportType">Start From Process : </label>

            <select
                id="reportType"
                wire:model.live="reportType"
            >
                <option value="CUTTING">CUTTING</option>
                <option value="TEST">TEST</option>
            </select>
        </div>

        {{-- FILTER FORM --}}
        <div class="ng-report-filters">
            {{ $this->form }}
        </div>

        {{-- REPORT TABLE --}}
        <div class="ng-report-table">
            {{ $this->table }}
        </div>

    </div>

    <style>
        /* Reduce spacing between all report sections */
        .ng-report-layout {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        /* Report type label and dropdown on the same line */
        .ng-report-type {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 !important;
            padding: 0 !important;
        }

        .ng-report-type label {
            font-weight: 600;
            white-space: nowrap;
            margin: 0;
        }

        .ng-report-type select {
            width: 180px;
            padding: 5px 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: white;
            color: #111827;
        }

        /* Remove extra spacing around the filter form */
        .ng-report-filters {
            margin: 0 !important;
            padding: 0 !important;
        }

        .ng-report-filters > * {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }

        /* Report table */
        .ng-report-table {
            margin-top: 0 !important;
        }

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

        /* Reduce Filament page content spacing */
        .fi-page-content {
            gap: 4px !important;
        }

        .fi-page-content > * {
            margin-block: 0 !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(() => {
                if (window.Alpine && Alpine.store('sidebar')) {
                    Alpine.store('sidebar').isOpen = false;
                }
            }, 200);
        });
    </script>
</x-filament-panels::page>
