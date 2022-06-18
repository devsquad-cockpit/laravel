import {format} from 'sql-formatter';


export default (id, codeType) => ({
    id,
    codeType,
    toCopy: null,
    copied: false,
    init() {
        if (this.codeType === 'json') {
            this.$refs.toCopy.textContent = JSON.stringify(JSON.parse(document.getElementById(this.id).innerText), null, 2);
        } else if (this.codeType === 'sql') {
            this.$refs.toCopy.textContent = format(document.getElementById(this.id).innerText);
        } else {
            this.$refs.toCopy.textContent = document.getElementById(this.id).innerText;
        }
        this.toCopy = this.$refs.toCopy.textContent;
    },
    copy() {
        if (!navigator.clipboard) {
            this.copyFallback();
            return;
        }

        const self = this;
        navigator.clipboard.writeText(this.toCopy).then(function () {
            self.toggleCopied();
        }, function (err) {
            // console.error('Async: Could not copy text: ', err);
        });
    },
    copyFallback(text) {
        let textArea = document.createElement("textarea");
        textArea.value = this.toCopy;

        // Avoid scrolling to bottom
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            let successful = document.execCommand('copy');
            let msg = successful ? 'successful' : 'unsuccessful';
            this.toggleCopied();
            // console.log('Fallback: Copying text command was ' + msg);
        } catch (err) {
            // console.error('Fallback: Oops, unable to copy', err);
        }

        document.body.removeChild(textArea);
    },
    toggleCopied() {
        this.copied = true;
        setTimeout(() => {
            this.copied = false;
        }, 1500);
    }
});
