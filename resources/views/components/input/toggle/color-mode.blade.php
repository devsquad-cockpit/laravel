<x-cockpit::input.wrapper x-data="{ value: darkMode }"
                          x-init="$watch('value', function(value) {
                              $refs['theme'].dispatchEvent(new Event('change'));
                          })"
                          x-id="['toggle-label']">
    <input type="hidden" name="theme" id="theme" value="1"/>
    <input type="checkbox" name="theme" id="theme" value="0" class="hidden" x-model="value" x-ref="theme"
           x-on:change="darkMode = value"
           x-on:load="value = darkMode"/>

    <div class="flex justify-center">
        <x-cockpit::input.label for="theme"
                                x-bind:id="$id('toggle-label')"
                                x-on:click="$refs['theme-button'].click(); $refs['theme-button'].focus()">
            Theme
        </x-cockpit::input.label>
    </div>

    <div class="flex justify-center">
        <button x-ref="theme-button" x-on:click="value = !value" type="button" role="switch"
                x-bind:aria-checked="value"
                x-bind:aria-labelledby="$id('toggle-label')"
                x-bind:class="!value ? 'bg-yellow-500' : 'bg-gray-600'"
                class="relative w-12 h-6 py-3 px-0 items-center inline-flex rounded-full focus:ring-none">
        <span x-bind:class="!value ? 'translate-x-6' : 'translate-x-1'"
              class="absolute transition"
              aria-hidden="true">
            <x-cockpit-icons icon="sun" outline class="h-5 w-auto text-white" x-show="!value"/>
            <x-cockpit-icons icon="moon" class="h-4 w-auto text-white" x-show="value"/>
        </span>
        </button>

        <x-cockpit::input.error-message for="theme"/>
    </div>
</x-cockpit::input.wrapper>
