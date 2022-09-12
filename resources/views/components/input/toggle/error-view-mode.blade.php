<x-cockpit::input.wrapper class="flex items-center justify-center"
                          x-data="{ value: errorTopBarNavigation }"
                          x-init="$watch('value', function(value) {
                              $refs['errorTopBarNavigation'].dispatchEvent(new Event('change'));
                          })"
                          x-id="['toggle-label']">
    <input type="hidden" name="errorTopBarNavigation" id="errorTopBarNavigation" value="1"/>
    <input type="checkbox" name="errorTopBarNavigation" id="errorTopBarNavigation" value="0" class="hidden"
           x-model="value"
           x-ref="errorTopBarNavigation"
           x-on:change="errorTopBarNavigation = value"
           x-on:load="value = errorTopBarNavigation"/>

    <div class="flex items-center space-x-2">
        <x-cockpit::input.label for="errorTopBarNavigation"
                                x-bind:id="$id('toggle-label')"
                                x-on:click="$refs['errorTopBarNavigation-button'].click(); $refs['errorTopBarNavigation-button'].focus()">
            View Mode
        </x-cockpit::input.label>

        <button x-on:click="value = false">
            <x-cockpit::icons.view-mode-sidebar
                    class="w-10 h-auto border-2 rounded-lg overflow-hidden"
                    x-bind:class="{ 'border-transparent': value, 'border-primary': !value}"/>
        </button>
        <button x-on:click="value = true">
            <x-cockpit::icons.view-mode-topbar
                    class="w-10 h-auto border-2 rounded-lg overflow-hidden"
                    x-bind:class="{ 'border-transparent': !value, 'border-primary': value}"/>
        </button>
    </div>

    <x-cockpit::input.error-message for="errorTopBarNavigation"/>
</x-cockpit::input.wrapper>
