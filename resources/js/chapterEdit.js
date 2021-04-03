Quill.register('modules/imageCompress', ImageCompress);

let InlineBlot = Quill.import('blots/inline');
class PopupBlot extends InlineBlot {
    static create(popupContent) {
        let node = super.create(popupContent);
        node.setAttribute('data-popup', popupContent);
        return node;
    }

  static formats(domNode) {
    return domNode.getAttribute('data-popup') || true;
  }

  format(name, value) {
    if (name === 'popup' && value) {
      this.domNode.setAttribute('data-popup', value);
    } else {
      super.format(name, value);
    }
  }

  formats() {
    let formats = super.formats();
    formats['popup'] = PopupBlot.formats(this.domNode);
    return formats;
  }
}
PopupBlot.blotName = 'popup';
PopupBlot.className = 'quill-popup';
PopupBlot.tagName = 'A';
Quill.register({'formats/popup': PopupBlot});


const Tooltip = Quill.import('ui/tooltip');
class PopupTooltip extends Tooltip {
    constructor(quill, bounds) {
        super(quill, bounds);
        this.preview = this.root.querySelector('.ql-preview');
        this.textbox = this.root.querySelector('.ql-popuptext');
        this.listen();
    }
    listen() {
        this.textbox.addEventListener('keydown', event => {
            switch(event.key) {
                // case 'Enter':
                //     this.save();
                //     event.preventDefault();
                //     break;
                case 'Escape':
                    this.cancel();
                    event.preventDefault();
                    break;
            }
        });
        this.root.querySelector('a.ql-action').addEventListener('click', event => {
            if (this.root.classList.contains('ql-editing')) {
                this.save();
            } else {
                this.edit('popup', this.preview.textContent);
            }
            event.preventDefault();
        });
        this.root.querySelector('a.ql-remove').addEventListener('click', event => {
            if (this.linkRange != null) {
                const range = this.linkRange;
                this.restoreFocus();
                this.quill.formatText(...range, 'popup', false, 'user');
                delete this.linkRange;
            }
            event.preventDefault();
            this.hide();
        });
        this.quill.on(
            'selection-change',
            (range, oldRange, source) => {
                if (range == null) return;
                if (range.length === 0 && source === 'user') {
                    const [popup, offset] = this.quill.scroll.descendant(
                        PopupBlot,
                        range.index,
                    );
                    if (popup != null) {
                        this.linkRange = [range.index - offset, popup.length()];
                        const preview = PopupBlot.formats(popup.domNode);
                        this.preview.textContent = preview;
                        this.show();
                        this.position(this.quill.getBounds(...this.linkRange));
                        return;
                    }
                } else {
                    delete this.linkRange;
                }
                this.hide();
            },
        );
    }
    show() {
        super.show();
        this.root.removeAttribute('data-mode');
        this.root.classList.add('ql-popup');
    }
    restoreFocus() {
        const { scrollTop } = this.quill.scrollingContainer;
        this.quill.focus();
        this.quill.scrollingContainer.scrollTop = scrollTop;
    }
    cancel() {
        this.hide();
    }
    edit(mode = 'popup', preview = null) {
        this.root.classList.remove('ql-hidden');
        this.root.classList.add('ql-editing');
        if (preview != null) {
            this.textbox.value = preview;
        } else if (mode !== this.root.getAttribute('data-mode')) {
            this.textbox.value = '';
        }
        this.position(this.quill.getBounds(this.quill.selection.savedRange));
        this.textbox.select();
        this.root.setAttribute('data-mode', mode);
    }
    save() {
        let { value } = this.textbox;
        const { scrollTop } = this.quill.root;
        if (this.linkRange) {
            this.quill.formatText(
                ...this.linkRange,
                'popup',
                value,
                'user'
            );
            delete this.linkRange;
        } else {
            this.restoreFocus();
            this.quill.format('popup', value, 'user');
        }
        this.quill.root.scrollTop = scrollTop;
        this.hide();
    }
}
PopupTooltip.TEMPLATE = `
<div class="py-1">
    <p class="mb-1"><strong>Popup note:</strong></p>
    <p class="ql-preview whitespace-pre-wrap mb-1 leading-4"></p>
    <textarea name="popup" class="ql-popuptext border border-gray-500 leading-4" rows="3"></textarea>
    <div class="text-center">
        <a class="ql-action"></a>
        <a class="ql-remove"></a>
    </div>
</div>
`;



const QuillModule = Quill.import('core/module');
class PopupModule extends QuillModule {
  constructor(quill, options) {
    super(quill, options);
    this.tooltip = new PopupTooltip(this.quill, options.bounds);
    this.quill.getModule('toolbar').addHandler('popup', this.popupHandler.bind(this));
  }
  popupHandler(value) {
    if (value) {
      var range = this.quill.getSelection();
      if (range == null || range.length === 0) return;
      var preview = this.quill.getText(range);
      this.tooltip.show();
      this.tooltip.edit();
      this.tooltip.position(this.quill.getBounds(range.index, range.length));
    }
  }
}
Quill.register('modules/popup', PopupModule);

document.addEventListener("DOMContentLoaded", function() {
    var hiddenVal = document.getElementById("chapter-content");
    
    var quill = new Quill('#chapter-editor', {
        theme: 'snow',
        placeholder: "...",
        modules: {
            toolbar: {
                container: ['bold', 'italic', 'underline', 'strike', 'image', { 'align': [] }, 'removeRaw', 'popup'],
                handlers: {
                    removeRaw: () => {
                        var current_text = JSON.stringify(quill.getContents());
                        var filtered_text = current_text.split(/\\n/g).filter((t) => {
                            const en_t = t.replace(/[^a-zA-Z0-9 .?"'!&(){}:;~=-]/g, "");
                            return (en_t.length / t.length > .2);
                        }).join("\\n");
                        quill.setContents(JSON.parse(filtered_text).ops);
                    },
                }
            },
            imageCompress: {
                quality: 0.7, // default
                maxWidth: 640, // default
                maxHeight: 640, // default
                imageType: 'image/jpeg', // default
                debug: false, // default
            },
            popup: {},
        },        
        formats: [
            'bold', 'italic', 'underline', 'strike', 'image', 'align', 'removeRaw', 'popup'
        ],
        clipboard: {
            allowed: {
                tags: ['b', 'strong', 'u', 's', 'i', 'p', 'br'],
                attributes: []
            },
        },
    });

    function isJSON(str) {
        try {
            return (JSON.parse(str) && !!str);
        } catch (e) {
            return false;
        }
    }
    // console.log("isJSON? ", isJSON(content));
    if (isJSON(content)) {
        // remove nbsp
        let jsonContent = content.replace(/&nbsp;/g, " ");
        quill.setContents(JSON.parse(jsonContent).ops);
    
        // sync the hidden input
        hiddenVal.value = JSON.stringify(quill.getContents());
    }
    else {
        quill.setText(content);
    
        // sync the hidden input
        hiddenVal.value = JSON.stringify(quill.getContents());
    }
    
    quill.on('text-change', function(delta, oldDelta, source) {
        // sync the hidden input
        hiddenVal.value = JSON.stringify(quill.getContents());
        // console.log(JSON.parse(hiddenVal.value));
    });
});