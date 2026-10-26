<x-filament-panels::page>
    <div class="ng-report-layout">

        {{-- REPORT CATEGORY + REPORT TYPE + OK (same line) --}}
        <div class="ng-report-selectors">

            {{-- REPORT CATEGORY --}}
            <div class="ng-report-field">
                <label for="reportCategory">REPORT BY :</label>

                {{-- no .live: value is only sent to the server when OK is clicked --}}
                <select id="reportCategory" wire:model="reportCategory">
                    <option value="DIVISION">DIVISION</option>
                    <option value="PROCESS">PROCESS</option>
                </select>
            </div>

            {{-- REPORT TYPE + OK BUTTON --}}
            <div class="ng-report-field">
                <label for="reportType">Start From Process :</label>

                <select id="reportType" wire:model="reportType">
                    <option value="CUTTING">CUTTING</option>
                    <option value="TEST">TEST</option>
                </select>

                <button
                    type="button"
                    class="ng-report-ok"
                    wire:click="applyReport"
                    wire:loading.attr="disabled"
                    wire:target="applyReport"
                >
                    <span wire:loading.remove wire:target="applyReport">OK</span>
                    <span wire:loading wire:target="applyReport">Loading...</span>
                </button>
            </div>

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
        .ng-report-layout {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .ng-report-selectors {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            align-items: center;
            gap: 24px;
            margin: 0 !important;
            padding: 0 !important;
        }

        .ng-report-field {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 !important;
            padding: 0 !important;
        }

        .ng-report-field label {
            font-weight: 600;
            white-space: nowrap;
            margin: 0;
        }

        .ng-report-field select {
            width: 180px;
            padding: 5px 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: white;
            color: #111827;
        }

        .ng-report-ok {
            padding: 5px 18px;
            border-radius: 6px;
            background: #059669;
            color: white;
            font-weight: 600;
            cursor: pointer;
        }

        .ng-report-ok:hover { background: #047857; }
        .ng-report-ok:disabled { opacity: .6; cursor: wait; }

        .ng-report-filters {
            margin: 0 !important;
            padding: 0 !important;
        }

        .ng-report-filters > * {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }

        .ng-report-table { margin-top: 0 !important; }

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

        .fi-page-content { gap: 4px !important; }
        .fi-page-content > * { margin-block: 0 !important; }
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