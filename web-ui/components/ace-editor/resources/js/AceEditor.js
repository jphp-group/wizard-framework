

class bundle_aceeditor_AceEditor extends UX.TextInputControl {
  get theme() {
    return this._theme;
  }

  set theme(value) {
    this._theme = value;

    if (this.dom.data('--ace-editor')) {
      this.dom.data('--ace-editor').setTheme('ace/theme/' + value);
    }
  }

  get mode() {
    return this._mode;
  }

  set mode(value) {
    this._mode = value;

    if (this.dom.data('--ace-editor')) {
      this.dom.data('--ace-editor').getSession().setMode('ace/mode/' + value);
    }
  }

  get text() {
    if (this.dom.data('--ace-editor')) {
      return this.dom.data('--ace-editor').getValue();
    }

    return this._text || '';
  }

  set text(value) {
    this._text = value;

    if (this.dom.data('--ace-editor')) {
      this.dom.data('--ace-editor').setValue(value);
    }
  }

  get font() {
    return UX.Font.getFromDom(this.dom);
  }

  set font(value) {
    UX.Font.applyToDom(this.dom, value);

    if (this.dom.data('--ace-editor')) {
      this.dom.data('--ace-editor').setFontSize(this.font.size);
    }
  }

  get tabSize() {
    return this._tabSize || 4;
  }

  set tabSize(value) {
    this._tabSize = value;

    if (this.dom.data('--ace-editor')) {
      this.dom.data('--ace-editor').getSession().setTabSize(value);
    }
  }

  get editable() {
    return this._editable === undefined ? true : this._editable;
  }

  set editable(value) {
    this._editable = value;

    if (this.dom.data('--ace-editor')) {
      this.dom.data('--ace-editor').setReadOnly(!value);
    }
  }

  /**
   * @param {string} name
   * @param {Array} args
   */
  callEditorCommand(name, args) {
    if (this.dom.data('--ace-editor')) {
      const editor = this.dom.data('--ace-editor');
      editor[name].apply(editor, args);
    }
  }

  requestFocus() {
    if (this.dom.data('--ace-editor')) {
      this.dom.data('--ace-editor').focus();
    }
  }

  createDom() {
    const dom = jQuery('<div class="form-control ux-text-input-control ux-ace-editor" />');

    dom.ready(() => {
      const editor = window.ace.edit(dom[0]);
      dom.data('--ace-editor', editor);

      if (this._theme) { this.theme = this._theme; }
      if (this._mode) { this.mode = this._mode; }
      if (this._text) { this.text = this._text; }
      if (this._tabSize) { this.tabSize = this._tabSize; }
      if (this._editable !== undefined) { this.editable = this._editable; }

      this.font = this.font;
    });

    return dom;
  }
}

window['bundle.AceEditor'] = bundle_aceeditor_AceEditor;