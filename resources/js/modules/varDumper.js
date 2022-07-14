require('../symfony')

export default (dump) => ({
    dump,

    init() {
        const match = this.dump.html_dump.match(/sf-dump-\d+/);

        if (!match) {
            return;
        }

        window.Sfdump(match[0], {
            maxDepth: 3,
            maxStringLength:160
        });
    }
})
