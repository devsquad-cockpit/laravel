<x-cockpit::input.wrapper class="flex items-center justify-center"
                          x-data="{ value: errorLayoutNavBar }"
                          x-init="$watch('value', function(value) {
                              $refs['errorLayoutNavBar'].dispatchEvent(new Event('change'));
                          })"
                          x-id="['toggle-label']">
    <input type="hidden" name="errorLayoutNavBar" id="errorLayoutNavBar" value="1"/>
    <input type="checkbox" name="errorLayoutNavBar" id="errorLayoutNavBar" value="0" class="hidden" x-model="value"
           x-ref="errorLayoutNavBar"
           x-on:change="errorLayoutNavBar = value"
           x-on:load="value = errorLayoutNavBar"/>

    <div class="flex items-center space-x-2" x-show="darkMode">
        <x-cockpit::input.label for="errorLayoutNavBar"
                                x-bind:id="$id('toggle-label')"
                                x-on:click="$refs['errorLayoutNavBar-button'].click(); $refs['errorLayoutNavBar-button'].focus()">
            View Mode
        </x-cockpit::input.label>
        
        <button x-on:click="value = false">
            <x-cockpit::icons.view-mode-topbar
                    class="w-10 h-auto border-2 rounded-lg overflow-hidden"
                    x-bind:class="{ 'border-transparent': value, 'border-primary': !value}"/>
        </button>
        <button x-on:click="value = true">
            <x-cockpit::icons.view-mode-sidebar
                    class="w-10 h-auto border-2 rounded-lg overflow-hidden"
                    x-bind:class="{ 'border-transparent': !value, 'border-primary': value}"/>
        </button>
    </div>

    <x-cockpit::input.error-message for="errorLayoutNavBar"/>
</x-cockpit::input.wrapper>
