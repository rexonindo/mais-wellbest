<x-filament-panels::page>
    {{ $this->table }}
    <div class="mt-8">
        <h2 class="text-xl font-bold mb-4">NG Summary by NG Name</h2>

        <table class="w-full text-sm border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-3 py-2 text-left">NG Name</th>
                    <th class="border px-3 py-2 text-right">Total Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->getNgSummaryByNGName() as $row)
                    <tr>
                        <td class="border px-3 py-2">{{ $row->ng_nm }}</td>
                        <td class="border px-3 py-2 text-right">{{ number_format($row->total_qty) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-8">
        <h2 class="text-xl font-bold mb-4">NG Summary by Part No</h2>

        <table class="w-full text-sm border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-3 py-2 text-left">Part No</th>
                    <th class="border px-3 py-2 text-right">Total Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->getNgSummaryByPartNo() as $row)
                    <tr>
                        <td class="border px-3 py-2">{{ $row->itm_cd }}</td>
                        <td class="border px-3 py-2 text-right">{{ number_format($row->total_qty) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>    
    <div class="mt-8">
        <h2 class="text-xl font-bold mb-4">NG Summary by Process</h2>

        <table class="w-full text-sm border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-3 py-2 text-left">Process</th>
                    <th class="border px-3 py-2 text-right">Total Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->getNgSummaryByProcess() as $row)
                    <tr>
                        <td class="border px-3 py-2">{{ $row->proc_nm }}</td>
                        <td class="border px-3 py-2 text-right">{{ number_format($row->total_qty) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>    

</x-filament-panels::page>
