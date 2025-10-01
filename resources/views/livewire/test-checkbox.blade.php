<div class="p-6">
    <h2 class="text-xl font-bold mb-4">Test Checkbox Binding</h2>

    <label class="block mb-2">
        <input type="checkbox" wire:model="selected.1"> Dataset 1
    </label>
    <label class="block mb-2">
        <input type="checkbox" wire:model="selected.2"> Dataset 2
    </label>
    <label class="block mb-2">
        <input type="checkbox" wire:model="selected.3"> Dataset 3
    </label>

    <pre class="bg-gray-100 p-3 rounded">Selected: {{ json_encode($selected) }}</pre>
</div>
