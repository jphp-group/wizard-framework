import Utils from './util/Utils';
import UILoader from "../NX/UILoader";

const KEY_CODES = {
  Enter: 13,
  Backspace: 8,
  Tab: 9,
  Cancel: 0x03,
  Clear: 0x0C,
  Shift: 0x10,
  Ctrl: 0x11,
  Alt: 0x12,
  Pause: 0x13,
  CapsLock: 0x14,
  Esc: 0x1B,
  Escape: 0x1B,
  Space: 0x20,
  PageUp: 0x21,
  PageDown: 0x22,
  End: 0x23,
  Home: 0x24,
  Left: 0x25,
  Up: 0x26,
  Right: 0x27,
  Down: 0x28,
  Comma:0x2C,
  Delete: 0x7F,
  F1: 0x70, F2: 0x71, F3: 0x72, F4: 0x73, F5: 0x74, F6: 0x75, F7: 0x76, F8: 0x77, F9: 0x78, F10: 0x79, F11: 0x7A, F12: 0x7B,
  PrintScreen: 0x9A,
  Insert: 0x9B
};

/**
 * Base HTML Node class.
 **/
class Node {
  constructor(dom) {
    this.__observers = [];

    if (dom === undefined) {
      this.dom = this.createDom();

      if (!(this.dom instanceof jQuery)) {
        throw new Error("Method createDom() must return instance of an jQuery object");
      }
    } else {
      if (dom instanceof jQuery) {
        this.dom = dom;
      } else {
        throw new Error("Non-jquery object cannot be passed into Node.construct()");
      }
    }

    this.dom.data('--wrapper', this);

    this.dom.on('dblclick.Node', (e) => {
      this.trigger('click-2x', e);
    });

    this.dom.on('click.Node', (e) => {
      switch (e.which) {
        case 1:
          this.trigger('click-left', e); break;
        case 2:
          this.trigger('click-middle', e); break;
        case 3:
          this.trigger('click-right', e); break;
      }
    });

    const keyEventBuilder = (event) => {
      return (e) => {
        let found = false;

        for (let key in KEY_CODES) {
          const value = KEY_CODES[key];

          if (value === e.keyCode) {
            this.trigger(event + '-' + key.toLowerCase(), e);

            if (event.shiftKey) this.trigger(`${event}-shift+${key.toLowerCase()}`, e);
            if (event.ctrlKey) this.trigger(`${event}-ctrl+${key.toLowerCase()}`, e);
            if (event.altKey) this.trigger(`${event}-alt+${key.toLowerCase()}`, e);

            found = true;
          }
        }

        switch (event.keyCode) {
          case KEY_CODES.Up:
          case KEY_CODES.Right:
          case KEY_CODES.Down:
          case KEY_CODES.Left:
            this.trigger(event + '-anydirection', e);

            if (event.shiftKey) this.trigger(`${event}-shift+anydirection`, e);
            if (event.ctrlKey) this.trigger(`${event}-ctrl+anydirection`, e);
            if (event.altKey) this.trigger(`${event}-alt+anydirection`, e);

            break;
        }

        if (event.hasOwnProperty('char') && !found) {
          const char = event.char.toString().toLowerCase();
          this.trigger(event + '-' + char, e);

          if (event.shiftKey) this.trigger(`${event}-shift+${char}`, e);
          if (event.ctrlKey) this.trigger(`${event}-ctrl+${char}`, e);
          if (event.altKey) this.trigger(`${event}-alt+${char}`, e);

          if ('0123456789'.indexOf(char) > -1) {
            this.trigger(event + '-anydigit', e);

            if (event.shiftKey) this.trigger(`${event}-shift+anydigit`, e);
            if (event.ctrlKey) this.trigger(`${event}-ctrl+anydigit`, e);
            if (event.altKey) this.trigger(`${event}-alt+anydigit`, e);
          }

          if ('qwertyuiopasdfghjklzxcvbnm'.indexOf(char) > -1) {
            this.trigger(event + '-anyletter', e);

            if (event.shiftKey) this.trigger(`${event}-shift+anyletter`, e);
            if (event.ctrlKey) this.trigger(`${event}-ctrl+anyletter`, e);
            if (event.altKey) this.trigger(`${event}-alt+anyletter`, e);
          }
        }
      };
    };

    this.dom.on('keydown.Node', keyEventBuilder('keydown'));
    this.dom.on('keyup.Node', keyEventBuilder('keyup'));
  }

  /**
   * @param {function} handler
   */
  __forEachObservers(handler) {
    for (let observer of this.__observers) {
      handler(observer);
    }
  }

  __triggerPropertyChange(name, newValue) {
    this.__forEachObservers(observer => {
      const oldValue = this[name];
      observer.triggerPropertyChange(name, oldValue, newValue)
    });
  }

  /**
   * @returns {string}
   */
  get uuid() {
    return this.dom.attr('uuid')
  }

  /**
   * @param {string} value
   */
  set uuid(value) {
    this.dom.removeClass(this.uuid);
    this.dom.attr('uuid', value);
    this.dom.addClass(value);
  }

  get id() {
    return this.dom.attr('id')
  }

  set id(value) {
    this.dom.attr('id', value);
  }

  /**
   * @returns {Array}
   */
  get classes() {
    return this.dom.data('custom-classes') || [];
  }

  /**
   * @param {Array} value
   */
  set classes(value) {
    const oldClasses = this.classes;
    let classes = [];

    if (value instanceof Array) {
      classes = value;
    } else {
      classes = value.toString().split(' ');
    }

    this.dom.data('custom-classes', classes);

    if (oldClasses.length > 0) {
      this.dom.removeClass(oldClasses.join(' '));
    }

    this.dom.addClass(classes.join(' '));
  }

  get style() {
    return this.dom.attr('style');
  }

  set style(value) {
    this.dom.attr('style', value);
  }

  get visible() {
    let dom = this.dom;

    if (this.dom.data('--wrapper-dom')) {
      dom = this.dom.data('--wrapper-dom');
    }

    return dom.is(':visible');
  }

  set visible(value) {
    if (value) {
      this.show();
    } else {
      this.hide();
    }
  }

  get opacity() {
    return this.dom.css('opacity');
  }

  set opacity(value) {
    this.dom.css('opacity', value);
  }

  get enabled() {
    return !this.dom.prop("disabled");
  }

  set enabled(value) {
    this.dom.prop('disabled', !value);
  }

  get selectionEnabled() {
    return this.dom.css('user-select') !== 'none';
  }

  set selectionEnabled(value) {
    this.dom.css('user-select', value ? '' : 'none');
  }

  get focused() {
    return this.dom.is(':focus');
  }

  get x() {
    return this.dom.position().left;
  }

  set x(value) {
    this.dom.css({left: value});
  }

  get y() {
    return this.dom.position().top;
  }

  set y(value) {
    this.dom.css({top: value});
  }

  get position() {
    return [this.x, this.y];
  }

  set position(value) {
    if (value instanceof Array && value.length >= 2) {
      this.x = value[0];
      this.y = value[1];
    }
  }

  get width() {
    if (this.data('--width-percent')) {
      return this.data('--width-percent');
    }

    return this.dom.width()
  }

  set width(value) {
    this.dom.width(value);

    if (typeof value === 'string' && value.indexOf('%') > -1) {
      this.data('--width-percent', value);

      const wrapperDom = this.dom.data('--wrapper-dom');
      if (wrapperDom) {
        this.dom.width('100%');
      }
    } else {
      this.data('--width-percent', null);
    }
  }

  get height() {
    if (this.data('--height-percent')) {
      return this.data('--height-percent');
    }

    return this.dom.height()
  }

  set height(value) {
    this.dom.height(value);

    if (typeof value === 'string' && value.indexOf('%') > -1) {
      this.data('--height-percent', value);

      const wrapperDom = this.dom.data('--wrapper-dom');
      if (wrapperDom) {
        this.dom.height('100%');
      }
    } else {
      this.data('--height-percent', null);
    }
  }

  get size() {
    return [this.width, this.height]
  }

  set size(value) {
    if (value instanceof Array && value.length >= 2) {
      this.width = value[0];
      this.height = value[1];
    }
  }

  get tooltip() {
    return this.dom.data('tooltip');
  }

  __setTooltip(tooltip) {
    this.dom.data('--tooltip', tooltip);

    /*if (this.dom.data('bs.tooltip')) {
      this.dom.tooltip('dispose');
    }*/

    if (tooltip) {
      const options = jQuery.extend({},
        { title: tooltip instanceof Node ? tooltip.dom : tooltip },
        this.tooltipOptions
      );

      this.dom.tooltip(options);
    }
  }

  set tooltip(tooltip) {
    if (this.tooltip === tooltip) return;

    this.__setTooltip(tooltip);
  }

  get tooltipOptions() {
    return this.dom.data('--tooltipOptions') || {};
  }

  set tooltipOptions(options) {
    this.dom.data('--tooltipOptions', options || {});
    this.__setTooltip(this.tooltip);
  }

  get padding() {
    return [
      Utils.toPt(this.dom.css('padding-top')),
      Utils.toPt(this.dom.css('padding-right')),
      Utils.toPt(this.dom.css('padding-bottom')),
      Utils.toPt(this.dom.css('padding-left'))
    ];
  }

  set padding(value) {
    if (value instanceof Array) {
      if (value.length >= 4) {
        this.dom.css({
          'padding-top': value[0], 'padding-right': value[1],
          'padding-bottom': value[2], 'padding-left': value[3]
        });
      } else if (value.length >= 2) {
        this.dom.css({
          'padding-top': value[0], 'padding-right': value[1],
          'padding-bottom': value[0], 'padding-left': value[1]
        });
      } else if (value.length >= 1) {
        this.dom.css('padding', value[0]);
      } else {
        this.dom.css('padding', 0);
      }
    } else {
      this.dom.css('padding', value);
    }
  }

  get cursor() {
    return this.dom.css('cursor');
  }

  set cursor(value) {
    this.dom.css('cursor', value);
  }

  get parent() {
    let parent = null;

    if (this.dom.data('--wrapper-dom')) {
      parent = this.dom.data('--wrapper-dom').parent();
    } else {
      parent = this.dom.parent();
    }

    if (!parent) {
      return null;
    }

    return Node.getFromDom(parent);
  }

  get userData() {
    return this.dom.data('--user-data');
  }

  set userData(value) {
    this.dom.data('--user-data', value);
  }

  createDom() {
    throw new Error("Cannot call abstract method createDom()");
  }

  requestFocus() {
    this.focus();
  }

  relocate(x, y) {
    this.position = [x, y];
  }

  resize(width, height) {
    this.size = [width, height];
  }

  focus() {
    this.dom.focus();
  }

  css(value) {
    return this.dom.css(...arguments);
  }

  data(params) {
    if (arguments.length === 1) {
      return this.dom.data(...arguments);
    } else {
      this.dom.data(...arguments);
      return this;
    }
  }

  lookup(selector) {
    const dom = this.dom.find(selector).first();

    if (dom) {
      return Node.getFromDom(dom);
    }

    return null;
  }

  lookupAll(selector) {
    const nodes = [];

    this.dom.find(selector).each(() => {
      nodes.push(Node.getFromDom(this));
    });

    return nodes;
  }

  toFront() {
    const parent = this.parent;

    if (parent) {
      if (parent['childToFront']) {
        parent.childToFront(this);
      }
    }
  }

  toBack() {
    const parent = this.parent;

    if (parent) {
      if (parent['childToBack']) {
        parent.childToBack(this);
      }
    }
  }

  free() {
    const wrapperDom = this.dom.data('--wrapper-dom');

    if (wrapperDom) {
      wrapperDom.remove();
    } else {
      this.dom.detach();
    }

    return this;
  }

  show() {
    let dom = this.dom;
    dom.css('display', '');

    if (this.dom.data('--wrapper-dom')) {
      dom = this.dom.data('--wrapper-dom');
      dom.css('display', '');
    }

    return this;
  }

  hide() {
    let dom = this.dom;
    dom.hide();

    if (this.dom.data('--wrapper-dom')) {
      dom = this.dom.data('--wrapper-dom');
      dom.hide();
    }

    return this;
  }

  toggle() {
    if (this.visible) {
      this.show();
    } else {
      this.hide();
    }

    return this;
  }

  /**
   * @param {object} properties
   * @param {object} options
   */
  animate(properties, options) {
    this.dom.animate(properties, options);
  }

  /**
   * Stop animation.
   */
  stopAllAnimate(clearQueue, jumpToEnd, callback) {
    this.dom.stop(clearQueue, jumpToEnd);

    if (callback) {
      callback();
    }
  }

  /**
   * Stop animation by queue.
   * @param queue
   * @param clearQueue
   * @param jumpToEnd
   */
  stopAnimate(queue, clearQueue, jumpToEnd, callback) {
    this.dom.stop(queue, clearQueue, jumpToEnd);

    if (callback) {
      callback();
    }
  }

  on(event, callback) {
    this.dom.on(event, (event) => {
      event.sender = this;
      callback.call(this, event);
    });

    return this;
  }

  /**
   * @param {string} event
   * @returns {Node}
   */
  off(event) {
    this.dom.off(event);
    return this;
  }

  /**
   * @param {string} event
   * @param params
   * @returns {*}
   */
  trigger(event, params) {
    return this.dom.trigger(event, params);
  }

  /**
   * @param {string} id
   * @returns {Node}
   */
  child(id) {
    return null;
  }

  /**
   * Returns inner Nodes as array.
   * @returns {Array}
   */
  innerNodes() {
    return [];
  }

  /**
   * @param object
   */
  loadSchema(object) {
    for (const prop in object) {
      if (object.hasOwnProperty(prop)) {
        if (prop[0] === '_') {
          continue;
        }

        let value = object[prop];

        if (value.hasOwnProperty('_')) {
          const uiLoader = new UILoader();
          value = uiLoader.load(value);
        }

        switch (prop) {
          default:
            this[prop] = value;
            break;
        }
      }
    }

    if (object.classes) {
      this.classes = object.classes;
    }
  }

  static getFromDom(jqueryObject) {
    if (jqueryObject === null || jqueryObject.length === 0) {
      return null;
    }

    if (jqueryObject instanceof jQuery) {
      let wrapper = jqueryObject.data('--wrapper');
      return wrapper ? wrapper : new Node(jqueryObject);
    }

    throw new Error("Node.getFromDom(): 1 argument must be an jQuery object");
  }
}

export default Node;
