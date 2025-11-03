<x-filament::page>
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Left Side: Form -->
        <div class="w-full lg:w-1/3">
            <x-slot name="heading">Create New Process Flow</x-slot>
            {{ $this->form }}            
            <div class="mt-4">
                </br>
                <x-filament::button wire:click="save" color="primary">
                    Save
                </x-filament::button>
            </div>
        </div>

        <!-- Right Side: Table -->
        <div class="w-full lg:w-2/3">
            <x-slot name="heading">Process Flow List</x-slot>
            {{ $this->table }}
        </div>
    </div>
</x-filament::page>

