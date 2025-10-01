<div>
    <label for="selectData">Pilih Data:</label>
    <select id="selectData" wire:model.live="selected" multiple size="3" class="border rounded p-2">
        <option value="1">Satu</option>
        <option value="2">Dua</option>
        <option value="3">Tiga</option>
    </select>

    <div class="mt-2">
        <strong>Dipilih:</strong>
        <pre>{{ json_encode($selected) }}</pre>
    </div>
</div>
