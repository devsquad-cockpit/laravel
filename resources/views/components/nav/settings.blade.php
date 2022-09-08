<x-cockpit::dropdown>
    <x-slot name="handler">
        <x-cockpit-icons icon="cog" class="h-6 h-6 cursor-pointer" outline/>
    </x-slot>

    <div class="space-y-3" role="none">
        <x-cockpit::input.toggle.color-mode/>
        <div class="flex justify-center items-center">
            <x-cockpit::input.select name="editor" x-data="{}" x-model="preferredEditor"
                                     wrapper-class="text-center">
                <option value="phpstorm">PhpStorm</option>
                <option value="vscode">VsCode</option>
            </x-cockpit::input.select>
        </div>
    </div>
</x-cockpit::dropdown>
