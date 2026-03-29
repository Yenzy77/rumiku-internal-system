<div>
    <x-custom-select 
        wire:model.live="status" 
        class="!py-2 !px-3 !rounded-xl text-[11px] uppercase tracking-wider border-gray-100 bg-gray-50/50 w-full" 
        :options="['todo' => 'TODO', 'in_progress' => 'IN PROGRESS', 'review' => 'IN REVIEW', 'done' => 'DONE']" 
        placeholder="Select Status"
    />
</div>
