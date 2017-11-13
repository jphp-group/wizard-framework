import UILoader from './UILoader';
import Container from './../UX/Container';
import Utils from './../UX/util/Utils';

class Fragment {
  constructor(uiResource) {
    this.uiLoader = new UILoader();
    this.ui = {};
    this._content = null;

    this.loadUi(uiResource);
  }

  get content() {
    return this._content;
  }

  bindOne(id, handler) {
    if (this._binds) {
      this._binds[id] = handler;
    } else {
      this._binds = {}
      this._binds[id] = handler;
    }

    if (this._content) {
      var sub = this._content.child(id);

      if (sub) {
        for (var event in handler) {
          if (handler.hasOwnProperty(event)) {
            sub.on(event, (e) => {
              handler[event].call(this, e);
            });
          }
        }
      } else {
        console.warn(`Child '${id}' is not defined`);
      }
    }
  }

  bind(handlers) {
    this._binds = handlers;

    if (this._content) {
      for (var id in handlers) {
        if (handlers.hasOwnProperty(id)) {
          this.bindOne(id, handlers[id]);
        }
      }
    }
  }

  loadUi(uiResource) {
    if (uiResource instanceof String || typeof uiResource === 'string') {
      this.uiLoader.loadFromUrl(uiResource, (node) => {
          this._content = node;
          this.afterLoad();
      }, this);
    } else {
      this._content = this.uiLoader.load(uiResource, this);
      this.afterLoad();
    }
  }

  afterLoad() {
    if (this._binds) {
      this.bind(this._binds);
    }

    this._refreshUi();

    if (this._rootDom) {
      this._rootDom.empty().append(this._content.dom);
    }
  }

  _refreshUi() {
    if (this.ui) {
      for (var key in this) {
        if (this.ui.hasOwnProperty(key)) {
          delete this[key];
        }
      }
    }

    this.ui = {};

    var self = this;

    const refresh = function (node) {
      if (node instanceof Container) {
        var children = node.children();

        for (var i = 0; i < children.length; i++) {
          var child = children[i];

          if (child) {
            var id = child.id;

            if (id && !self.ui[id]) {
              self.ui[id] = child;
            }

            refresh(child);
          }
        }
      }
    }

    refresh(this._content);

    for (var key in this.ui) {
      if (this.ui.hasOwnProperty(key)) {
        this[key] = this.ui[key];
      }
    }
  }

  load(fragment) {
    if (fragment instanceof Fragment) {
      fragment.parent = this;
      fragment.render(this._rootDom);
    } else {
      console.warn('load(): 1 argument must be an fragment instance');
    }
  }

  render(root) {
    var dom;

    if (Utils.isElement(root)) {
      dom = jQuery(root);
    } else {
      if (root instanceof jQuery) {
        dom = root;
      } else {
        dom = jQuery(document).find(root).first();
      }
    }

    this._rootDom = dom;

    if (this._content) {
      dom.children().detach();
      dom.append(this._content.dom);
    }
  }
}

export default Fragment;
