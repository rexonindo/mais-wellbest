<div class="overflow-x-auto">
    <table class="min-w-full border border-gray-300 rounded-lg text-sm">
        <thead class="bg-gray-100 sticky top-0">
            <tr>
                <th class="px-3 py-2 text-left border-b">Seq No</th>
                <th class="px-3 py-2 text-left border-b">Process Code</th>
                <th class="px-3 py-2 text-left border-b">Cavity</th>
                <th class="px-3 py-2 text-left border-b">Shoot Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-1 border-b">{{ $row->seq_no }}</td>
                    <td class="px-3 py-1 border-b">{{ $row->proc_cd }}</td>
                    <td class="px-3 py-1 border-b text-right">{{ $row->cav }}</td>
                    <td class="px-3 py-1 border-b text-right">{{ $row->shoot_qty }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
