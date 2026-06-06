<x-filament-panels::page>
    <form wire:submit.prevent="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex flex-wrap gap-3">
            <x-filament-panels::form.actions
                :actions="$this->getFormActions()"
            />
        </div>
    </form>
</x-filament-panels::page>
