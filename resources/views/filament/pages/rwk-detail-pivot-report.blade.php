<x-filament-panels::page>
    <div class="rwk-report-layout">

        {{-- REPORT CATEGORY + REPORT TYPE + OK (same line) --}}
        <div class="rwk-report-selectors">

            {{-- REPORT CATEGORY --}}
            <div class="rwk-report-field">
                <label for="reportGroup">Group By :</label>

                {{-- no .live: value is only sent to the server when OK is clicked --}}
                <select id="reportGroup" wire:model="reportGroup">
                    <option value="CATEGORY">CATEGORY</option>
                    <option value="DIVISION">DIVISION</option>
                    <option value="PROCESS">PROCESS</option>
                </select>
            </div>

            {{-- REPORT TYPE + OK BUTTON --}}
            <div class="rwk-report-field">
                <label for="reportType">Start From Process :</label>

                <select id="reportType" wire:model="reportType">
                    <option value="CUTTING">CUTTING</option>
                    <option value="TEST">TEST</option>
                </select>

                <button
                    type="button"
                    class="rwk-report-ok"
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
        <div class="rwk-report-filters">
            {{ $this->form }}
        </div>

        {{-- REPORT TABLE --}}
        <div class="rwk-report-table">
            {{ $this->table }}
        </div>

    </div>

    <style>
        .rwk-report-layout {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .rwk-report-selectors {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            align-items: center;
            gap: 24px;
            margin: 0 !important;
            padding: 0 !important;
        }

        .rwk-report-field {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 !important;
            padding: 0 !important;
        }

        .rwk-report-field label {
            font-weight: 600;
            white-space: nowrap;
            margin: 0;
        }

        .rwk-report-field select {
            width: 180px;
            padding: 5px 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: white;
            color: #111827;
        }

        .rwk-report-ok {
            padding: 5px 18px;
            border-radius: 6px;
            background: #059669;
            color: white;
            font-weight: 600;
            cursor: pointer;
        }

        .rwk-report-ok:hover { background: #047857; }
        .rwk-report-ok:disabled { opacity: .6; cursor: wait; }

        .rwk-report-filters {
            margin: 0 !important;
            padding: 0 !important;
        }

        .rwk-report-filters > * {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }

        .rwk-report-table { margin-top: 0 !important; }

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