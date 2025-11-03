<x-filament::page>
    {{-- Header with company logo and name --}}
    <div class="text-center mb-8">
        <img src="{{ asset('images/logo-wellbest-with-name.png') }}" alt="Company Logo" class="h-32 mx-auto object-contain">
    </div>
    <div class="text-center mb-8 text-gray-700 text-base leading-relaxed">

        <p>&nbsp;&nbsp;</p>       
        <p>
            <h1><strong>Manufacturing Actual Input System (MAIS)</strong></h1>   
            is a system used to record and monitor the real-time inputs of manufacturing process&nbsp;such as raw materials, labor, machine usage, etc.</br>
            It focuses on tracking what is truly used, rather than what was planned or estimated, to improve accuracy, efficiency, and cost control.
        </p>
        <p>&nbsp;</p>
        <p><strong>Benefits of this system :</strong></p>
        <ul>
        <li><strong>Real-time accuracy</strong>: Tracks actual usage of materials, labor, and energy, reducing discrepancies between planned and actual costs.</li>
        <li><strong>Improved cost control</strong>: Helps identify waste, overuse, or inefficiencies, enabling better budgeting and resource allocation.</li>
        <li><strong>Enhanced productivity</strong>: Pinpoints bottlenecks or underperforming areas by comparing actual input data with expected benchmarks.</li>
        <li><strong>Better decision-making</strong>: Provides reliable data for managers to make informed adjustments to processes or supply chains.</li>
        <li><strong>Quality assurance</strong>: Ensures that inputs meet required standards, supporting consistent product quality.</li>
        <li><strong>Compliance and traceability</strong>: Maintains detailed records of inputs for audits, certifications, and regulatory compliance</li>
        </ul>
        <p></br></br>Thank you</p>
        <p><strong>Management</strong></p>
    </div> 
    </br>    
    {{-- Stats Grid --}}
    <div class="flex justify-center gap-6">

        <x-filament::card>
            <div class="flex flex-col items-center justify-center text-center h-full py-1">
                <h2 class="text-sm font-medium text-gray-500">Total Running Product</h2>
                <p class="mt-1 text-xl font-bold text-gray-900">
                    {{ number_format($ttlProduct ?? 0, 0, '.', ',') }}
                </p>
            </div>
        </x-filament::card>

        <x-filament::card>
            <div class="flex flex-col items-center justify-center text-center h-full py-1">
                <h2 class="text-sm font-medium text-gray-500">Total Running Machine</h2>
                <p class="mt-1 text-xl font-bold text-gray-900">
                    {{ number_format($ttlMachine ?? 0, 0, '.', ',') }}
                </p>
            </div>
        </x-filament::card>

        <x-filament::card>
            <div class="flex flex-col items-center justify-center text-center h-full py-1">
                <h2 class="text-sm font-medium text-gray-500">Total Plan Qty</h2>
                <p class="mt-1 text-xl font-bold text-gray-900">
                    {{ number_format($ttlPlanQty ?? 0, 0, '.', ',') }}
                </p>
            </div>
        </x-filament::card>

        <x-filament::card>
            <div class="flex flex-col items-center justify-center text-center h-full py-1">
                <h2 class="text-sm font-medium text-gray-500">Total Output Qty</h2>
                <p class="mt-1 text-xl font-bold text-gray-900">
                    {{ number_format($ttlOutQty ?? 0, 0, '.', ',') }}
                </p>
            </div>
        </x-filament::card>

        <x-filament::card>
            <div class="flex flex-col items-center justify-center text-center h-full py-1">
                <h2 class="text-sm font-medium text-gray-500">Total Outstanding Qty</h2>
                <p class="mt-1 text-xl font-bold text-gray-900">
                    {{ number_format($ttlOSQty ?? 0, 0, '.', ',') }}
                </p>
            </div>
        </x-filament::card>

    </div>

</x-filament::page>
