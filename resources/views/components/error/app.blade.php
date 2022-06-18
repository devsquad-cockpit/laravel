<x-cockpit::error.section {{ $attributes }} class="p-4">
    <x-cockpit::error.section.wrapper title="Routing">
        <x-cockpit::error.section.content type="Category">Short content</x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="Category">
            Longer Content right here super long content it just keeps going longer content right here super long content it just keeps going
        </x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="Category" copyable code-type="json">
            { "glossary": { "title": "example glossary", "GlossDiv": { "title": "S", "GlossList": { "GlossEntry": { "ID": "SGML", "SortAs": "SGML", "GlossTerm": "Standard Generalized Markup Language", "Acronym": "SGML", "Abbrev": "ISO 8879:1986", "GlossDef": { "para": "A meta-markup language, used to create markup languages such as DocBook.", "GlossSeeAlso": ["GML", "XML"] }, "GlossSee": "markup" } } } } }
        </x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="Category" copyable code-type="sql">
            SELECT column1, column2 FROM table WHERE column1='value'
        </x-cockpit::error.section.content>
    </x-cockpit::error.section.wrapper>
    <x-cockpit::error.section.wrapper title="View"></x-cockpit::error.section.wrapper>
</x-cockpit::error.section>
