import Utils from './util/Utils';

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
   * @returns {UIMediator}
   */
  get uiMediator() {
    return this.dom.data('--ui-mediator') || null;
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
    this.__triggerPropertyChange('id', value);
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
    this.__triggerPropertyChange('style', value);
    this.dom.attr('style', value);
  }

  get visible() {
    return this.dom.is(':visible');
  }

  set visible(value) {
    this.__triggerPropertyChange('visible', value);

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
    this.__triggerPropertyChange('opacity', value);
    this.dom.css('opacity', value);
  }

  get enabled() {
    return !this.dom.prop("disabled");
  }

  set enabled(value) {
    this.__triggerPropertyChange('enabled', value);
    this.dom.prop('disabled', !value);
  }

  get focused() {
    return this.dom.is(':focus');
  }

  get x() {
    return this.dom.position().left;
  }

  set x(value) {
    this.__triggerPropertyChange('x', value);
    this.dom.css({left: value});
  }

  get y() {
    return this.dom.position().top;
  }

  set y(value) {
    this.__triggerPropertyChange('y', value);
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
    return this.dom.width()
  }

  set width(value) {
    this.__triggerPropertyChange('width', value);
    this.dom.width(value);

    if (typeof value === 'string' && value.indexOf('%') > -1) {
      this.data('--width-percent', value);
    } else {
      this.data('--width-percent', null);
    }
  }

  get height() {
    return this.dom.height()
  }

  set height(value) {
    this.__triggerPropertyChange('height', value);
    this.dom.height(value);

    if (typeof value === 'string' && value.indexOf('%') > -1) {
      this.data('--height-percent', value);
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

  get padding() {
    return [
      Utils.toPt(this.dom.css('padding-top')),
      Utils.toPt(this.dom.css('padding-right')),
      Utils.toPt(this.dom.css('padding-bottom')),
      Utils.toPt(this.dom.css('padding-left'))
    ];
  }

  set padding(value) {
    this.__triggerPropertyChange('padding', value);

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
    this.__triggerPropertyChange('userData', value);
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
    this.dom.css('display', '');
    return this;
  }

  hide() {
    this.dom.hide();
    return this;
  }

  toggle() {
    this.dom.toggle();
    return this;
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
   * @param object
   */
  loadSchema(object) {
    for (const prop in object) {
      if (object.hasOwnProperty(prop)) {
        if (prop[0] === '_') {
          continue;
        }

        const value = object[prop];

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

  /**
   * @param {UIMediator} uiMediator
   */
  connectToMediator(uiMediator) {
    this.dom.data('--ui-mediator', uiMediator);
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
