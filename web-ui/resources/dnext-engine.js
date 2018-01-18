(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\NX\\App.js":[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var App = function () {
  function App() {
    _classCallCheck(this, App);

    this._callbacks = {};
  }

  /**
   * @param {string} message
   */


  _createClass(App, [{
    key: "log",
    value: function log(message) {
      console.log(message);
    }
  }, {
    key: "launch",
    value: function launch() {
      this.socket = new WebSocket("/dnext/ws/");

      this.socket.onmessage = function (event) {
        var data = JSON.parse(event.data);

        if (data.id && data.message) {
          var callback = this._callbacks[data.id];

          if (callback) {
            callback(data);
          }
        }
      };
    }
  }, {
    key: "send",
    value: function send(message, data, callback) {
      data.message = message;
      data.id = Math.random().toString(36).substring(7);

      if (callback) {
        this._callbacks[data.id] = callback;
      }

      this.socket.send(JSON.stringify(data));
    }
  }]);

  return App;
}();

exports.default = App;

},{}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\NX\\AppMediator.js":[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _UILoader = require("./UILoader");

var _UILoader2 = _interopRequireDefault(_UILoader);

var _Node = require("../UX/Node");

var _Node2 = _interopRequireDefault(_Node);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var AppMediator = function () {
  function AppMediator() {
    _classCallCheck(this, AppMediator);
  }

  _createClass(AppMediator, [{
    key: "init",
    value: function init(rootDom) {
      this.rootDom = rootDom;
      this.activated = true;
      this._callbacks = [];
      this._nodes = {};

      var uuid = sessionStorage.getItem('AppMediator.uuid');

      if (!uuid) {
        this.uuid = Math.random().toString(36).substring(7);
        sessionStorage.setItem('AppMediator.uuid', this.uuid);
      } else {
        this.uuid = uuid;
      }
    }

    /**
     * @param contextUrl
     * @param wsUrl
     * @param sessionId
     */

  }, {
    key: "startWatching",
    value: function startWatching(contextUrl, wsUrl, sessionId) {
      var _this = this;

      this.contextUrl = contextUrl;
      this.wsUrl = wsUrl;
      this.sessionId = sessionId;

      var loc = window.location;
      var newUri = '';

      if (loc.protocol === "https:") {
        newUri = "wss:";
      } else {
        newUri = "ws:";
      }

      newUri += "//" + loc.host;
      newUri += wsUrl;

      this.ws = new WebSocket(newUri);

      this.ws.onerror = function () {
        if (sessionStorage.getItem('AppMediator.reloading')) {
          setTimeout(function () {
            return window.location.reload(true);
          }, 200);
        }
      };

      this.ws.onopen = function () {
        sessionStorage.setItem('AppMediator.reloading', false);

        _this.send('initialize', {});
        _this.sendIfCan('ui-ready', {
          location: {
            contextUrl: contextUrl,
            hash: window.location.hash ? window.location.hash.substr(1) : '',
            path: window.location.pathname,
            host: window.location.host,
            port: window.location.port,
            protocol: window.location.protocol,
            target: window.location.target
          }
        });
      };

      this.ws.onclose = function () {
        _this.ws = null;

        if (_this.node) {
          _this.node.free();
        }
      };

      var handlers = {
        'ui-render': this.triggerRenderView,
        'ui-reload': this.triggerReload,
        'ui-alert': this.triggerAlert,
        'ui-set-property': this.triggerSetProperty,
        'ui-call-method': this.triggerCallMethod,
        'ui-event-link': this.triggerOnEventLink,
        'ui-create-node': this.triggerCreateNode
      };

      this.ws.onmessage = function (event) {
        var message = JSON.parse(event.data);
        var type = message.type;

        console.debug("AppMediator.receive", message);

        if (handlers.hasOwnProperty(type)) {
          handlers[type].call(_this, message);
        } else {
          switch (type) {
            case "system-console-log":
              var text = message['message'];

              switch (message['kind']) {
                case 'warn':
                  console.warn(text);
                  break;
                case 'error':
                  console.error(text);
                  break;
                case 'info':
                  console.info(text);
                  break;
                case 'debug':
                  console.debug(text);
                  break;
                case 'trace':
                  console.trace(text);
                  break;
                case 'clear':
                  console.clear();
                  break;
                default:
                  console.log(text);
                  break;
              }

              break;
            case "system-eval":
              var script = message.script,
                  callback = message.callback;

              var result = eval(script);

              if (callback) {
                callback(result);
              }

              break;
            case "history-push":
              var url = message['url'];
              var title = message['title'];
              var hash = message['hash'];

              if (window.location.pathname === url) {
                if (title !== undefined) {
                  document.title = title;
                }
                if (hash !== undefined) {
                  window.location.hash = hash;
                }

                break;
              }

              window.history.pushState(null, message['title'], url);

              if (title !== undefined) {
                document.title = title;
              }
              if (hash !== undefined) {
                window.location.hash = hash;
              }

              break;

            case "page-set-properties":
              if (message['title'] !== undefined) {
                document.title = message['title'];
              }

              if (message['hash'] !== undefined) {
                window.location.hash = message['hash'];
              }

              break;
          }
        }
      };

      setInterval(function () {
        var docVisible = !document.hidden;

        if (docVisible !== _this.activated) {
          _this.activated = docVisible;

          if (_this.activated) {
            _this.sendIfCan('activate', {});
          }
        }
      }, 1);
    }
  }, {
    key: "parseValue",
    value: function parseValue(value) {
      var _this2 = this,
          _arguments = arguments;

      if (value instanceof Object) {
        if (value.hasOwnProperty('$node')) {
          return this.findNodeByUuidGlobally(value['$node']);
        } else if (value.hasOwnProperty('$createNode')) {
          var schema = value['$createNode'];

          var uiLoader = new _UILoader2.default();
          return uiLoader.load(schema);
        } else if (value.hasOwnProperty('$callable')) {
          var uuid = value['$callable'];

          return function () {
            _this2.sendIfCan('callback-trigger', {
              'uuid': uuid,
              'args': _arguments
            });
          };
        }
      }

      if (value instanceof Array) {
        for (var i = 0; i < value.length; i++) {
          value[i] = this.parseValue(value[i]);
        }

        return value;
      }

      if (value instanceof Object) {
        var newValue = {};

        for (var key in value) {
          if (value.hasOwnProperty(key)) {
            newValue[key] = this.parseValue(value[key]);
          }
        }

        return newValue;
      }

      return value;
    }
  }, {
    key: "prepareValue",
    value: function prepareValue(value) {
      if (value instanceof _Node2.default) {
        if (value.uuid !== undefined) {
          return { '$node': value.uuid };
        } else {
          console.error('Cannot send unregistered node', value);
          return null;
        }
      }

      if (value instanceof Array) {
        for (var i = 0; i < value.length; i++) {
          value[i] = this.prepareValue(value[i]);
        }

        return value;
      }

      if (value instanceof Object) {
        var newValue = {};

        for (var key in value) {
          if (value.hasOwnProperty(key)) {
            newValue[key] = this.prepareValue(value[key]);
          }
        }

        return newValue;
      }

      return value;
    }

    /**
     * @param node
     * @param data
     */

  }, {
    key: "sendUserInput",
    value: function sendUserInput(node, data) {
      var _this3 = this;

      if (!this.ws) {
        return;
      }

      if (!document.hidden) {
        setTimeout(function () {
          var newData = data;

          if (typeof data === "function") {
            newData = data();
          }

          _this3.sendIfCan('ui-user-input', {
            'uuid': node.uuid,
            'data': _this3.prepareValue(newData)
          });
        }, 0);
      } else {
        console.warn('Ignore User input');
      }
    }

    /**
     * @param type
     * @param message
     * @param callback
     * @returns {boolean}
     */

  }, {
    key: "sendIfCan",
    value: function sendIfCan(type, message, callback) {
      if (this.ws !== undefined) {
        this.send(type, message, callback);
        return true;
      } else {
        return false;
      }
    }

    /**
     * @param type
     * @param message
     * @param callback
     */

  }, {
    key: "send",
    value: function send(type, message, callback) {
      if (this.ws === undefined) {
        throw "Mediator is not in watching state.";
      }

      message.type = type;
      message.id = Math.random().toString(36).substring(7);
      message.sessionId = this.sessionId;
      message.sessionIdUuid = this.uuid;

      if (callback) {
        this._callbacks[message.id] = callback;
      }

      console.debug("AppMediator.send", message);

      this.ws.send(JSON.stringify(message));
    }
  }, {
    key: "findNodeByUuidGlobally",
    value: function findNodeByUuidGlobally(uuid) {
      var found = this.findNodeByUuid(uuid, this.node);

      if (found === null) {
        if (this._nodes.hasOwnProperty(uuid)) {
          found = this._nodes[uuid];
        }
      }

      if (found === null) {
        for (var key in this._nodes) {
          if (this._nodes.hasOwnProperty(key)) {
            var _found = this.findNodeByUuid(uuid, this._nodes[key]);

            if (_found !== null) {
              return _found;
            }
          }
        }
      }

      return found;
    }
  }, {
    key: "findNodeByUuid",
    value: function findNodeByUuid(uuid, node) {
      if (uuid === node.uuid) {
        return node;
      }

      var children = node.innerNodes();

      for (var i = 0; i < children.length; i++) {
        if (children[i].uuid === uuid) {
          return children[i];
        }

        var found = this.findNodeByUuid(uuid, children[i]);

        if (found !== null) {
          return found;
        }
      }

      return null;
    }
  }, {
    key: "triggerEvent",
    value: function triggerEvent(node, event, e) {
      var data = {
        type: e.type,
        which: e.which,
        result: e.result,
        namespace: e.namespace,
        position: [e.pageX, e.pageY]
      };

      this.sendIfCan('ui-trigger', {
        uuid: node.uuid,
        event: event,
        data: this.prepareValue(data)
      });
    }

    /**
     * Render new view.
     * @param message
     */

  }, {
    key: "triggerRenderView",
    value: function triggerRenderView(message) {
      var uiLoader = new _UILoader2.default();
      this.node = uiLoader.load(message['schema'], this);

      this.rootDom.empty();
      this.rootDom.append(this.node.dom);
    }
  }, {
    key: "triggerAlert",
    value: function triggerAlert(message) {
      var text = message['text'];
      alert(text);
    }
  }, {
    key: "triggerCallMethod",
    value: function triggerCallMethod(message) {
      var uuid = message['uuid'];
      var method = message['method'];
      var args = this.parseValue(message['args'] || []);

      var node = this.findNodeByUuidGlobally(uuid);

      if (node !== null) {
        node[method].apply(node, args);
      } else {
        console.warn("Failed to set property, node with uuid = " + uuid + " is not found");
      }
    }
  }, {
    key: "triggerSetProperty",
    value: function triggerSetProperty(message) {
      var uuid = message['uuid'];
      var property = message['property'];
      var value = this.parseValue(message['value']);

      var node = this.findNodeByUuidGlobally(uuid);

      if (node !== null) {
        node[property] = value;
      } else {
        console.warn("Failed to set property, node with uuid = " + uuid + " is not found");
      }
    }
  }, {
    key: "triggerOnEventLink",
    value: function triggerOnEventLink(message) {
      var _this4 = this;

      var uuid = message['uuid'];
      var event = message['event'];

      var node = this.findNodeByUuidGlobally(uuid);

      if (node !== null) {
        node.off(event + ".AppMediator");

        node.on(event + ".AppMediator", function (e) {
          _this4.triggerEvent(node, event, e);
        });
      } else {
        console.warn("Failed to link event " + event + ", node with uuid = " + uuid + " is not found");
      }
    }
  }, {
    key: "triggerCreateNode",
    value: function triggerCreateNode(message) {
      var schema = message['schema'];

      var uiLoader = new _UILoader2.default();
      var node = uiLoader.load(schema, this);

      this._nodes[node.uuid] = node;
    }
  }, {
    key: "triggerReload",
    value: function triggerReload(message) {
      sessionStorage.setItem('AppMediator.reloading', true);
      setTimeout(function () {
        return window.location.reload(true);
      }, 50);
    }
  }]);

  return AppMediator;
}();

exports.default = new AppMediator();

},{"../UX/Node":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Node.js","./UILoader":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\NX\\UILoader.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\NX\\NX.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _App = require('./App');

var _App2 = _interopRequireDefault(_App);

var _UILoader = require('./UILoader');

var _UILoader2 = _interopRequireDefault(_UILoader);

var _AppMediator = require('./AppMediator');

var _AppMediator2 = _interopRequireDefault(_AppMediator);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
  App: _App2.default, UILoader: _UILoader2.default, AppMediator: _AppMediator2.default
};

},{"./App":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\NX\\App.js","./AppMediator":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\NX\\AppMediator.js","./UILoader":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\NX\\UILoader.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\NX\\UILoader.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Node = require('./../UX/Node');

var _Node2 = _interopRequireDefault(_Node);

var _Container = require('./../UX/Container');

var _Container2 = _interopRequireDefault(_Container);

var _UX = require('./../UX/UX');

var _UX2 = _interopRequireDefault(_UX);

var _AppMediator = require('./AppMediator');

var _AppMediator2 = _interopRequireDefault(_AppMediator);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var UILoader = function () {
  function UILoader() {
    _classCallCheck(this, UILoader);
  }

  _createClass(UILoader, [{
    key: 'linkToMediator',
    value: function linkToMediator(node, data) {
      var watchedEvents = data['_watchedEvents'];
      if (watchedEvents !== undefined) {
        var _iteratorNormalCompletion = true;
        var _didIteratorError = false;
        var _iteratorError = undefined;

        try {
          var _loop = function _loop() {
            var watchedEvent = _step.value;

            node.on(watchedEvent + '.AppMediator', function (e) {
              _AppMediator2.default.triggerEvent(node, watchedEvent, e);
            });
          };

          for (var _iterator = watchedEvents[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
            _loop();
          }
        } catch (err) {
          _didIteratorError = true;
          _iteratorError = err;
        } finally {
          try {
            if (!_iteratorNormalCompletion && _iterator.return) {
              _iterator.return();
            }
          } finally {
            if (_didIteratorError) {
              throw _iteratorError;
            }
          }
        }
      }
    }

    /**
     * @param object
     * @returns {Node}
     */

  }, {
    key: 'load',
    value: function load(object) {
      if (object && (typeof object === 'undefined' ? 'undefined' : _typeof(object)) === "object") {
        var type = object['_'];

        if (!type) {
          throw new Error("Type is not defined in '_' property!");
        }

        var cls = _UX2.default[type];

        if (!cls) {
          cls = window[type];
        }

        if (!cls) {
          throw new Error('Type \'' + type + '\' is not defined');
        }

        var node = new cls();

        if (node instanceof _Node2.default) {
          if (node instanceof _Container2.default && jQuery.isArray(object['_content'])) {
            var children = object['_content'];

            for (var i = 0; i < children.length; i++) {
              var child = this.load(children[i], _AppMediator2.default);
              node.add(child);
            }
          }

          node.loadSchema(object);
          this.linkToMediator(node, object);
          return node;
        } else {
          throw new Error('Type \'' + type + '\' is not UI component class');
        }
      }
    }

    /**
     * @param {string} jsonString
     * @returns {Node}
     */

  }, {
    key: 'loadFromJson',
    value: function loadFromJson(jsonString) {
      return this.load(JSON.parse(jsonString));
    }

    /**
     *
     * @param {string} urlToJson
     * @param {function} callback
     */

  }, {
    key: 'loadFromUrl',
    value: function loadFromUrl(urlToJson, callback) {
      var _this = this;

      jQuery.getJSON(urlToJson, function (data) {
        callback(_this.load(data));
      });
    }
  }]);

  return UILoader;
}();

exports.default = UILoader;

},{"./../UX/Container":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Container.js","./../UX/Node":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Node.js","./../UX/UX":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\UX.js","./AppMediator":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\NX\\AppMediator.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\AnchorPane.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

var _Container2 = require('./Container');

var _Container3 = _interopRequireDefault(_Container2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var AnchorPane = function (_Container) {
  _inherits(AnchorPane, _Container);

  function AnchorPane() {
    _classCallCheck(this, AnchorPane);

    return _possibleConstructorReturn(this, (AnchorPane.__proto__ || Object.getPrototypeOf(AnchorPane)).apply(this, arguments));
  }

  _createClass(AnchorPane, [{
    key: 'createDom',
    value: function createDom() {
      var dom = _get(AnchorPane.prototype.__proto__ || Object.getPrototypeOf(AnchorPane.prototype), 'createDom', this).call(this);
      dom.addClass('ux-anchor-pane');
      return dom;
    }
  }, {
    key: 'createSlotDom',
    value: function createSlotDom(object) {
      object.dom.css('position', 'absolute');
      return object.dom;
    }
  }, {
    key: 'childToBack',
    value: function childToBack(object) {
      var dom = object.dom;
      dom.detach();

      this.dom.prepend(dom);
    }
  }, {
    key: 'childToFront',
    value: function childToFront(object) {
      var dom = object.dom;
      dom.detach();
      this.dom.append(dom);
    }
  }]);

  return AnchorPane;
}(_Container3.default);

exports.default = AnchorPane;

},{"./Container":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Container.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Button.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Labeled2 = require('./Labeled');

var _Labeled3 = _interopRequireDefault(_Labeled2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var kinds = ['success', 'primary', 'secondary', 'info', 'warning', 'danger', 'link', 'dark', 'light'];

var Button = function (_Labeled) {
  _inherits(Button, _Labeled);

  function Button() {
    _classCallCheck(this, Button);

    return _possibleConstructorReturn(this, (Button.__proto__ || Object.getPrototypeOf(Button)).apply(this, arguments));
  }

  _createClass(Button, [{
    key: 'createDom',
    value: function createDom() {
      var dom = jQuery('<button><span class="ux-labeled-text"></span></button>');
      dom.addClass('ux-labeled');
      dom.addClass('ux-button');

      dom.addClass('btn');
      dom.addClass('btn-default');

      return dom;
    }
  }, {
    key: 'outline',
    get: function get() {
      return !!this.dom.data('--outline');
    },
    set: function set(value) {
      var kind = this.kind;
      this.dom.data('--outline', !!value);
      this.kind = kind;
    }
  }, {
    key: 'kind',
    get: function get() {
      var dom = this.dom;

      var _iteratorNormalCompletion = true;
      var _didIteratorError = false;
      var _iteratorError = undefined;

      try {
        for (var _iterator = kinds[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
          var kind = _step.value;

          if (dom.hasClass('btn-' + kind) || dom.hasClass('btn-outline-' + kind)) {
            return kind;
          }
        }
      } catch (err) {
        _didIteratorError = true;
        _iteratorError = err;
      } finally {
        try {
          if (!_iteratorNormalCompletion && _iterator.return) {
            _iterator.return();
          }
        } finally {
          if (_didIteratorError) {
            throw _iteratorError;
          }
        }
      }

      return 'default';
    },
    set: function set(value) {
      this.dom.removeClass('btn-' + this.kind);
      this.dom.removeClass('btn-outline-' + this.kind);

      if (this.outline) {
        this.dom.addClass('btn-outline-' + value);
      } else {
        this.dom.addClass('btn-' + value);
      }
    }
  }]);

  return Button;
}(_Labeled3.default);

exports.default = Button;

},{"./Labeled":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Labeled.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Checkbox.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Labeled2 = require('./Labeled');

var _Labeled3 = _interopRequireDefault(_Labeled2);

var _AppMediator = require('../NX/AppMediator');

var _AppMediator2 = _interopRequireDefault(_AppMediator);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Checkbox = function (_Labeled) {
  _inherits(Checkbox, _Labeled);

  function Checkbox(text, graphic) {
    _classCallCheck(this, Checkbox);

    var _this = _possibleConstructorReturn(this, (Checkbox.__proto__ || Object.getPrototypeOf(Checkbox)).call(this, text, graphic));

    _this.dom.on('click.Checkbox', function () {
      _AppMediator2.default.sendUserInput(_this, { selected: _this.selected });
    });
    return _this;
  }

  _createClass(Checkbox, [{
    key: 'createDom',
    value: function createDom() {
      var dom = jQuery('<label><input type="checkbox"> <span class="ux-labeled-text"></span></label>');
      dom.addClass('ux-labeled');
      dom.addClass('ux-checkbox');
      return dom;
    }
  }, {
    key: 'checked',
    get: function get() {
      return this.dom.find('> input[type=checkbox]').prop('checked');
    },
    set: function set(value) {
      this.dom.find('> input[type=checkbox]').prop('checked', value);
    }
  }, {
    key: 'selected',
    get: function get() {
      return this.checked;
    },
    set: function set(value) {
      this.checked = value;
    }
  }]);

  return Checkbox;
}(_Labeled3.default);

exports.default = Checkbox;

},{"../NX/AppMediator":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\NX\\AppMediator.js","./Labeled":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Labeled.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Combobox.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

var _SelectControl2 = require('./SelectControl');

var _SelectControl3 = _interopRequireDefault(_SelectControl2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Combobox = function (_SelectControl) {
  _inherits(Combobox, _SelectControl);

  function Combobox() {
    _classCallCheck(this, Combobox);

    return _possibleConstructorReturn(this, (Combobox.__proto__ || Object.getPrototypeOf(Combobox)).apply(this, arguments));
  }

  _createClass(Combobox, [{
    key: 'createDom',
    value: function createDom() {
      var dom = _get(Combobox.prototype.__proto__ || Object.getPrototypeOf(Combobox.prototype), 'createDom', this).call(this);
      dom.addClass('ux-combobox');
      return dom;
    }
  }]);

  return Combobox;
}(_SelectControl3.default);

exports.default = Combobox;

},{"./SelectControl":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\SelectControl.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Container.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Node2 = require('./Node');

var _Node3 = _interopRequireDefault(_Node2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Container = function (_Node) {
  _inherits(Container, _Node);

  function Container(nodes) {
    _classCallCheck(this, Container);

    var _this = _possibleConstructorReturn(this, (Container.__proto__ || Object.getPrototypeOf(Container)).call(this));

    _this.add.apply(_this, arguments);
    _this.contentDom = _this.dom;
    return _this;
  }

  _createClass(Container, [{
    key: 'createSlotDom',
    value: function createSlotDom(object) {
      if (!(object instanceof _Node3.default)) {
        throw new TypeError('createSlotDom(): 1 argument must be instance of Node');
      }

      var dom = jQuery('<div/>').append(object.dom);
      dom.addClass('ux-slot');

      dom.data('--wrapper', object);
      object.dom.data('--wrapper-dom', dom);

      var width = object.data('--width-percent');
      var height = object.data('--height-percent');
      var visible = object.css('display') !== 'none';

      if (typeof width === 'string') {
        dom.width(width);
      }

      if (typeof width === 'string') {
        dom.height(height);
      }

      if (!visible) {
        dom.hide();
      }

      return dom;
    }
  }, {
    key: 'createDom',
    value: function createDom() {
      var dom = jQuery('<div></div>');
      dom.addClass('ux-container');

      return dom;
    }
  }, {
    key: 'child',
    value: function child(id) {
      var dom = this.contentDom.find('#' + id);

      if (dom && dom.length) {
        return _Node3.default.getFromDom(dom);
      }

      return null;
    }
  }, {
    key: 'count',
    value: function count() {
      return this.contentDom.children().length;
    }
  }, {
    key: 'children',
    value: function children() {
      var children = [];

      this.contentDom.children().each(function () {
        children.push(_Node3.default.getFromDom(jQuery(this)));
      });

      return children;
    }
  }, {
    key: 'innerNodes',
    value: function innerNodes() {
      return this.children();
    }
  }, {
    key: 'removeByIndex',
    value: function removeByIndex(index) {
      var child = this.children()[index];

      if (child) {
        child.free();
      }
    }
  }, {
    key: 'add',
    value: function add(nodes) {
      for (var i = 0; i < arguments.length; i++) {
        this.contentDom.append(this.createSlotDom(arguments[i]));
      }

      return this;
    }
  }, {
    key: 'insert',
    value: function insert(index, nodes) {
      index = index | 0;

      var children = this.contentDom.children();

      if (!children.length || index >= children.length) {
        return this.add.apply(this, _toConsumableArray(Array.prototype.slice.call(arguments, 1)));
      }

      nodes = Array.prototype.slice.call(arguments, 1);

      var i = 0;
      var self = this;

      this.dom.children().each(function () {
        if (index === i) {
          for (var k = 0; k < nodes.length; k++) {
            var slot = self.createSlotDom(nodes[k]);
            slot.insertBefore(this);
          }

          return false;
        }

        i++;
      });

      return this;
    }
  }, {
    key: 'clear',
    value: function clear() {
      this.contentDom.empty();
    }
  }, {
    key: 'show',
    value: function show() {
      this.dom.css('display', '');
      return this;
    }
  }, {
    key: 'align',
    get: function get() {
      return [this.verAlign, this.horAlign];
    },
    set: function set(value) {
      if (value instanceof Array && value.length >= 2) {
        this.horAlign = value[1];
        this.verAlign = value[0];
      }
    }
  }, {
    key: 'horAlign',
    get: function get() {
      if (this.dom.hasClass('ux-m-halign-right')) {
        return 'right';
      } else if (this.hasClass('ux-m-halign-center')) {
        return 'center';
      }

      return 'left';
    },
    set: function set(value) {
      this.dom.removeClass('ux-m-halign-left');
      this.dom.removeClass('ux-m-halign-right');
      this.dom.removeClass('ux-m-halign-center');

      this.dom.addClass('ux-m-halign-' + value);
    }
  }, {
    key: 'verAlign',
    get: function get() {
      if (this.dom.hasClass('ux-m-valign-bottom')) {
        return 'bottom';
      } else if (this.hasClass('ux-m-valign-center')) {
        return 'center';
      }

      return 'top';
    },
    set: function set(value) {
      this.dom.removeClass('ux-m-valign-top');
      this.dom.removeClass('ux-m-valign-bottom');
      this.dom.removeClass('ux-m-valign-center');

      this.dom.addClass('ux-m-valign-' + value);
    }
  }]);

  return Container;
}(_Node3.default);

exports.default = Container;

},{"./Node":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Node.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\HBox.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

var _Container2 = require('./Container');

var _Container3 = _interopRequireDefault(_Container2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var HBox = function (_Container) {
  _inherits(HBox, _Container);

  function HBox(nodes) {
    _classCallCheck(this, HBox);

    var _this = _possibleConstructorReturn(this, (HBox.__proto__ || Object.getPrototypeOf(HBox)).apply(this, arguments));

    _this.spacing = 0;
    _this.align = ['top', 'left'];
    return _this;
  }

  _createClass(HBox, [{
    key: 'createDom',
    value: function createDom() {
      var dom = _get(HBox.prototype.__proto__ || Object.getPrototypeOf(HBox.prototype), 'createDom', this).call(this);
      dom.addClass('ux-h-box');

      return dom;
    }
  }, {
    key: 'createSlotDom',
    value: function createSlotDom(object) {
      var dom = _get(HBox.prototype.__proto__ || Object.getPrototypeOf(HBox.prototype), 'createSlotDom', this).call(this, object);
      return dom;
    }
  }, {
    key: 'add',
    value: function add(nodes) {
      _get(HBox.prototype.__proto__ || Object.getPrototypeOf(HBox.prototype), 'add', this).apply(this, arguments);
      this.spacing = this.spacing;
    }
  }, {
    key: 'insert',
    value: function insert(index, nodes) {
      _get(HBox.prototype.__proto__ || Object.getPrototypeOf(HBox.prototype), 'insert', this).apply(this, arguments);
      this.spacing = this.spacing;
    }
  }, {
    key: 'fitHeight',
    get: function get() {
      return this.dom.hasClass('ux-m-fit-height');
    },
    set: function set(value) {
      if (value) {
        this.dom.addClass('ux-m-fit-height');
      } else {
        this.dom.removeClass('ux-m-fit-height');
      }
    }
  }, {
    key: 'spacing',
    get: function get() {
      return this._spacing;
    },
    set: function set(value) {
      this._spacing = value;
      var slots = this.dom.find('> div');

      slots.css('margin-right', value + 'px');
      slots.last().css('margin-right', 0);
    }
  }]);

  return HBox;
}(_Container3.default);

exports.default = HBox;

},{"./Container":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Container.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Hyperlink.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Labeled2 = require('./Labeled');

var _Labeled3 = _interopRequireDefault(_Labeled2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 *
 */
var Hyperlink = function (_Labeled) {
  _inherits(Hyperlink, _Labeled);

  function Hyperlink() {
    _classCallCheck(this, Hyperlink);

    return _possibleConstructorReturn(this, (Hyperlink.__proto__ || Object.getPrototypeOf(Hyperlink)).apply(this, arguments));
  }

  _createClass(Hyperlink, [{
    key: 'createDom',
    value: function createDom() {
      return jQuery('<a class="ux-hyperlink"><span class="ux-labeled-text"></span></a>');
    }
  }, {
    key: 'href',
    get: function get() {
      return this.dom.attr('href');
    },
    set: function set(value) {
      this.dom.attr('href', value);
    }
  }, {
    key: 'target',
    get: function get() {
      return this.dom.attr('target') || '_self';
    },
    set: function set(value) {
      this.dom.attr('target', value);
    }
  }]);

  return Hyperlink;
}(_Labeled3.default);

exports.default = Hyperlink;

},{"./Labeled":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Labeled.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Icon.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Node2 = require('./Node');

var _Node3 = _interopRequireDefault(_Node2);

var _Utils = require('./util/Utils');

var _Utils2 = _interopRequireDefault(_Utils);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Icon = function (_Node) {
  _inherits(Icon, _Node);

  function Icon() {
    _classCallCheck(this, Icon);

    return _possibleConstructorReturn(this, (Icon.__proto__ || Object.getPrototypeOf(Icon)).apply(this, arguments));
  }

  _createClass(Icon, [{
    key: 'createDom',
    value: function createDom() {
      return jQuery('<i class="material-icons ux-icon"></i>');
    }
  }, {
    key: 'kind',
    get: function get() {
      this.dom.text();
    },
    set: function set(value) {
      this.dom.text(value);
    }
  }, {
    key: 'color',
    get: function get() {
      return this.dom.css('color') || 'black';
    },
    set: function set(value) {
      this.dom.css('color', value);
    }
  }, {
    key: 'imageSize',
    get: function get() {
      return _Utils2.default.toPt(this.dom.css('font-size'));
    },
    set: function set(value) {
      this.dom.css('font-size', value);
    }
  }]);

  return Icon;
}(_Node3.default);

exports.default = Icon;

},{"./Node":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Node.js","./util/Utils":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\util\\Utils.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\ImageView.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Node2 = require('./Node');

var _Node3 = _interopRequireDefault(_Node2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ImageView = function (_Node) {
  _inherits(ImageView, _Node);

  function ImageView(image) {
    _classCallCheck(this, ImageView);

    var _this = _possibleConstructorReturn(this, (ImageView.__proto__ || Object.getPrototypeOf(ImageView)).call(this));

    _this.proportional = true;
    _this.displayType = 'origin';

    if (image !== undefined) {
      _this.source = image;
    }
    return _this;
  }

  _createClass(ImageView, [{
    key: 'createDom',
    value: function createDom() {
      var dom = jQuery('<div></div>');
      dom.addClass('ux-image-view');

      dom.css({
        display: 'inline-block',
        backgroundRepeat: 'no-repeat',
        backgroundSize: '100% 100%',
        backgroundPosition: '0 0'
      });
      return dom;
    }
  }, {
    key: 'source',
    get: function get() {
      var source = this.dom.css('background-image');

      if (source) {
        source = /^url\((['"]?)(.*)\1\)$/.exec(source);
        return source ? source[2] : null;
      }

      return null;
    },
    set: function set(value) {
      this.dom.css({ 'background-image': 'url(\'' + value + '\')' });

      if (this.displayType === 'origin') {
        this.dom.find('img').attr('src', value);
      }

      this.__triggerPropertyChange('source', value);
    }
  }, {
    key: 'centered',
    get: function get() {
      return this.dom.css('background-position') === '50% 50%';
    },
    set: function set(value) {
      this.dom.css('background-position', value ? '50% 50%' : '0 0');
      this.__triggerPropertyChange('centered', value);
    }
  }, {
    key: 'displayType',
    get: function get() {
      switch (this.dom.css('background-size')) {
        case '100% 100%':
          return 'filled';
        case 'cover':
          return 'cropped';
        case 'resized':
          return 'resized';

        case 'auto':
        case 'auto auto':
          return 'origin';

        default:
          return '';
      }
    },
    set: function set(type) {
      this.dom.find('img').remove();

      switch (type.toString().toLowerCase()) {
        case 'filled':
          this.dom.css('background-size', '100% 100%');
          break;
        case 'cropped':
          this.dom.css('background-size', 'cover');
          break;
        case 'resized':
          this.dom.css('background-size', 'contain');
          break;
        case 'origin':
          var source = this.source;
          this.dom.css('background-size', 'auto auto');
          this.dom.append(jQuery('<img style="visibility: hidden" />'));
          this.source = source;
          break;
      }

      this.__triggerPropertyChange('displayType', type);
    }
  }]);

  return ImageView;
}(_Node3.default);

exports.default = ImageView;

},{"./Node":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Node.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Label.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Labeled2 = require('./Labeled');

var _Labeled3 = _interopRequireDefault(_Labeled2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Label = function (_Labeled) {
    _inherits(Label, _Labeled);

    function Label() {
        _classCallCheck(this, Label);

        return _possibleConstructorReturn(this, (Label.__proto__ || Object.getPrototypeOf(Label)).apply(this, arguments));
    }

    _createClass(Label, [{
        key: 'createDom',
        value: function createDom() {
            var dom = jQuery('<span class="ux-labeled ux-label"><span class="ux-labeled-text"></span></span>');
            return dom;
        }
    }]);

    return Label;
}(_Labeled3.default);

exports.default = Label;

},{"./Labeled":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Labeled.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Labeled.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Node2 = require('./Node');

var _Node3 = _interopRequireDefault(_Node2);

var _Font = require('./paint/Font');

var _Font2 = _interopRequireDefault(_Font);

var _ImageView = require('./ImageView');

var _ImageView2 = _interopRequireDefault(_ImageView);

var _Utils = require('./util/Utils');

var _Utils2 = _interopRequireDefault(_Utils);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Labeled = function (_Node) {
  _inherits(Labeled, _Node);

  function Labeled(text, graphic) {
    _classCallCheck(this, Labeled);

    var _this = _possibleConstructorReturn(this, (Labeled.__proto__ || Object.getPrototypeOf(Labeled)).call(this));

    _this.textPreFormatted = false;
    _this.textType = 'text';
    _this.contentDisplay = 'left';
    _this.graphicTextGap = 4;
    _this.graphic = graphic;
    _this.text = text;
    _this.align = ['center', 'center'];
    return _this;
  }

  _createClass(Labeled, [{
    key: 'innerNodes',
    value: function innerNodes() {
      var result = [];

      if (this.graphic) {
        result.push(this.graphic);
      }

      return result;
    }
  }, {
    key: 'font',
    get: function get() {
      return _Font2.default.getFromDom(this.dom);
    },
    set: function set(value) {
      _Font2.default.applyToDom(this.dom, value);
      this.__triggerPropertyChange('value', value);
    }
  }, {
    key: 'align',
    get: function get() {
      return [this.verAlign, this.horAlign];
    },
    set: function set(value) {
      if (value instanceof Array && value.length >= 2) {
        this.horAlign = value[1];
        this.verAlign = value[0];
      }
    }
  }, {
    key: 'horAlign',
    get: function get() {
      if (this.dom.hasClass('ux-m-halign-right')) {
        return 'right';
      } else if (this.hasClass('ux-m-halign-center')) {
        return 'center';
      }

      return 'left';
    },
    set: function set(value) {
      this.dom.removeClass('ux-m-halign-left');
      this.dom.removeClass('ux-m-halign-right');
      this.dom.removeClass('ux-m-halign-center');

      this.dom.addClass('ux-m-halign-' + value);

      this.__triggerPropertyChange('horAlign', value);
    }
  }, {
    key: 'verAlign',
    get: function get() {
      if (this.dom.hasClass('ux-m-valign-bottom')) {
        return 'bottom';
      } else if (this.hasClass('ux-m-valign-center')) {
        return 'center';
      }

      return 'top';
    },
    set: function set(value) {
      this.dom.removeClass('ux-m-valign-top');
      this.dom.removeClass('ux-m-valign-bottom');
      this.dom.removeClass('ux-m-valign-center');

      this.dom.addClass('ux-m-valign-' + value);

      this.__triggerPropertyChange('verAlign', value);
    }
  }, {
    key: 'text',
    get: function get() {
      var dom = this.dom.find('> span.ux-labeled-text');

      if (this.textPreFormatted) {
        dom = dom.find('> pre');
      }

      switch (this.textType) {
        case 'text':
          return dom.text();
        case 'html':
          return dom.html();
      }

      return '';
    },
    set: function set(value) {
      var dom = this.dom.find('> span.ux-labeled-text');

      if (this.textPreFormatted) {
        dom = dom.find('> pre');
      }

      switch (this.textType) {
        case 'text':
          dom.text(value);
          break;

        case 'html':
          dom.html(value);
          break;
      }

      this.__triggerPropertyChange('text', value);
    }
  }, {
    key: 'textPreFormatted',
    get: function get() {
      return this.dom.find('> span.ux-labeled-text').has('> pre').length > 0;
    },
    set: function set(value) {
      if (this.textPreFormatted === value) {
        return;
      }

      var dom = this.dom.find('> span.ux-labeled-text');
      if (value) {
        dom.html('<pre>' + dom.html() + '</pre>');
      } else {
        dom.html(dom.find('> pre').html());
      }
    }
  }, {
    key: 'textColor',
    get: function get() {
      return this.dom.css('color');
    },
    set: function set(value) {
      this.dom.css('color', value ? value : '');

      this.__triggerPropertyChange('textColor', value);
    }
  }, {
    key: 'textType',
    get: function get() {
      return this._textType;
    },
    set: function set(value) {
      var text = this.text;
      var graphic = this.graphic;

      if (value) {
        this._textType = value.toString().toLowerCase();
      } else {
        this._textType = 'text';
      }

      this.text = text;
      this.graphic = graphic;
      this.__triggerPropertyChange('textType', value);
    }
  }, {
    key: 'contentDisplay',
    get: function get() {
      if (this.dom.first().hasClass('ux-graphic')) {
        if (this.dom.hasClass('ux-labeled-vertical')) {
          return 'top';
        } else {
          return 'left';
        }
      } else if (this.dom.last().hasClass('ux-graphic')) {
        if (this.dom.hasClass('ux-labeled-vertical')) {
          return 'bottom';
        } else {
          return 'right';
        }
      } else {
        return this._contentDisplay;
      }
    },
    set: function set(value) {
      var graphic = this.graphic;
      var graphicGap = this.graphicTextGap;
      this._contentDisplay = value;

      switch (value) {
        case 'top':
        case 'bottom':
          this.dom.addClass('ux-labeled-vertical');
          break;

        case 'right':
          this.dom.removeClass('ux-labeled-vertical');
          break;

        case 'left':
        default:
          this.dom.removeClass('ux-labeled-vertical');
          this._contentDisplay = 'left';
          break;
      }

      this.__triggerPropertyChange('contentDisplay', value);
      this.graphic = graphic;
      this.graphicTextGap = graphicGap;
    }
  }, {
    key: 'graphicTextGap',
    get: function get() {
      var grDom = this.dom.find('.ux-graphic');

      if (grDom.length) {
        var prop = 'margin-right';

        switch (this.contentDisplay) {
          case 'bottom':
            prop = 'margin-top';
            break;
          case 'right':
            prop = 'margin-left';
            break;
          case 'top':
            prop = 'margin-bottom';
            break;
        }

        return _Utils2.default.toPt(grDom.css(prop));
      } else {
        return this._graphicGap;
      }
    },
    set: function set(value) {
      this._graphicGap = value;

      var grDom = this.dom.find('.ux-graphic');

      if (grDom.length) {
        grDom.css('margin', 0);

        var prop = 'margin-right';

        switch (this.contentDisplay) {
          case 'bottom':
            prop = 'margin-top';
            break;
          case 'right':
            prop = 'margin-left';
            break;
          case 'top':
            prop = 'margin-bottom';
            break;
        }

        grDom.css(prop, value + 'px');
      }

      this.__triggerPropertyChange('contentDisplay', value);
    }
  }, {
    key: 'graphic',
    get: function get() {
      return _Node3.default.getFromDom(this.dom.find('.ux-graphic > *').first());
    },
    set: function set(node) {
      var graphicGap = this.graphicTextGap;
      this.dom.find('.ux-graphic').remove();

      if (node) {
        if (typeof node === 'string' || node instanceof String) {
          node = new _ImageView2.default(node);
        }

        var dom = jQuery('<span class="ux-graphic" />').append(node.dom);

        switch (this.contentDisplay) {
          case 'top':
          case 'left':
            this.dom.prepend(dom);
            break;
          case 'bottom':
          case 'right':
            this.dom.append(dom);
            break;
        }

        this.graphicTextGap = graphicGap;
      }

      this.__triggerPropertyChange('graphic', node);
    }
  }]);

  return Labeled;
}(_Node3.default);

exports.default = Labeled;

},{"./ImageView":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\ImageView.js","./Node":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Node.js","./paint/Font":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\paint\\Font.js","./util/Utils":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\util\\Utils.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\ListView.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

var _Container2 = require('./Container');

var _Container3 = _interopRequireDefault(_Container2);

var _Node = require('./Node');

var _Node2 = _interopRequireDefault(_Node);

var _AppMediator = require('../NX/AppMediator');

var _AppMediator2 = _interopRequireDefault(_AppMediator);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ListView = function (_Container) {
  _inherits(ListView, _Container);

  function ListView() {
    _classCallCheck(this, ListView);

    var _this = _possibleConstructorReturn(this, (ListView.__proto__ || Object.getPrototypeOf(ListView)).call(this));

    _this.spacing = 0;
    _this.align = ['center', 'left'];

    _this.dom.on('action.ListView', function () {
      var data = {
        selected: _this.selected,
        selectedIndex: _this.selectedIndex
      };

      _AppMediator2.default.sendUserInput(_this, data);
    });
    return _this;
  }

  _createClass(ListView, [{
    key: 'createDom',
    value: function createDom() {
      var dom = _get(ListView.prototype.__proto__ || Object.getPrototypeOf(ListView.prototype), 'createDom', this).call(this);
      dom.addClass('list-group');
      dom.addClass('ux-list-view');
      return dom;
    }
  }, {
    key: 'createSlotDom',
    value: function createSlotDom(object) {
      var _this2 = this;

      if (!(object instanceof _Node2.default)) {
        throw new TypeError('createSlotDom(): 1 argument must be instance of Node');
      }

      var dom = jQuery('<a href="#" class="list-group-item ux-slot" />').append(object.dom);

      dom.on('click.ListView', function (e) {
        dom.closest('.ux-list-view').find('> .ux-slot').removeClass('active');
        dom.addClass('active');

        _this2.dom.trigger('action');
        e.preventDefault();
        return false;
      });

      dom.data('--wrapper', object);
      object.dom.data('--wrapper-dom', dom);
      return dom;
    }
  }, {
    key: 'selectedIndex',
    get: function get() {
      var index = -1;
      var result = -1;

      this.dom.find('> .ux-slot').each(function () {
        index++;

        if (jQuery(this).hasClass('active')) {
          result = index;
          return true;
        }
      });

      return result;
    },
    set: function set(value) {
      var children = this.children();

      if (value >= 0 && value < children.length) {
        this.selected = children[value];
      } else {
        this.selected = null;
      }
    }
  }, {
    key: 'selected',
    get: function get() {
      var dom = this.dom.find('> .ux-slot.active').first();

      if (dom) {
        return _Node2.default.getFromDom(dom);
      }

      return null;
    },
    set: function set(object) {
      this.dom.find('> .ux-slot.active').removeClass('active');

      if (object instanceof _Node2.default) {
        object.dom.closest('.ux-slot').addClass('active');
      }
    }
  }]);

  return ListView;
}(_Container3.default);

exports.default = ListView;

},{"../NX/AppMediator":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\NX\\AppMediator.js","./Container":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Container.js","./Node":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Node.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Listbox.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

var _SelectControl2 = require('./SelectControl');

var _SelectControl3 = _interopRequireDefault(_SelectControl2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Listbox = function (_SelectControl) {
  _inherits(Listbox, _SelectControl);

  function Listbox() {
    _classCallCheck(this, Listbox);

    return _possibleConstructorReturn(this, (Listbox.__proto__ || Object.getPrototypeOf(Listbox)).apply(this, arguments));
  }

  _createClass(Listbox, [{
    key: 'createDom',
    value: function createDom() {
      var dom = _get(Listbox.prototype.__proto__ || Object.getPrototypeOf(Listbox.prototype), 'createDom', this).call(this);
      dom.prop('multiple', true);
      dom.addClass('ux-listbox');
      return dom;
    }
  }]);

  return Listbox;
}(_SelectControl3.default);

exports.default = Listbox;

},{"./SelectControl":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\SelectControl.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Node.js":[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Utils = require("./util/Utils");

var _Utils2 = _interopRequireDefault(_Utils);

var _UILoader = require("../NX/UILoader");

var _UILoader2 = _interopRequireDefault(_UILoader);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * Base HTML Node class.
 **/
var Node = function () {
  function Node(dom) {
    _classCallCheck(this, Node);

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


  _createClass(Node, [{
    key: "__forEachObservers",
    value: function __forEachObservers(handler) {
      var _iteratorNormalCompletion = true;
      var _didIteratorError = false;
      var _iteratorError = undefined;

      try {
        for (var _iterator = this.__observers[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
          var observer = _step.value;

          handler(observer);
        }
      } catch (err) {
        _didIteratorError = true;
        _iteratorError = err;
      } finally {
        try {
          if (!_iteratorNormalCompletion && _iterator.return) {
            _iterator.return();
          }
        } finally {
          if (_didIteratorError) {
            throw _iteratorError;
          }
        }
      }
    }
  }, {
    key: "__triggerPropertyChange",
    value: function __triggerPropertyChange(name, newValue) {
      var _this = this;

      this.__forEachObservers(function (observer) {
        var oldValue = _this[name];
        observer.triggerPropertyChange(name, oldValue, newValue);
      });
    }

    /**
     * @returns {string}
     */

  }, {
    key: "__setTooltip",
    value: function __setTooltip(tooltip) {
      this.dom.data('--tooltip', tooltip);

      /*if (this.dom.data('bs.tooltip')) {
        this.dom.tooltip('dispose');
      }*/

      if (tooltip) {
        var options = jQuery.extend({}, { title: tooltip instanceof Node ? tooltip.dom : tooltip }, this.tooltipOptions);

        this.dom.tooltip(options);
      }
    }
  }, {
    key: "createDom",
    value: function createDom() {
      throw new Error("Cannot call abstract method createDom()");
    }
  }, {
    key: "requestFocus",
    value: function requestFocus() {
      this.focus();
    }
  }, {
    key: "relocate",
    value: function relocate(x, y) {
      this.position = [x, y];
    }
  }, {
    key: "resize",
    value: function resize(width, height) {
      this.size = [width, height];
    }
  }, {
    key: "focus",
    value: function focus() {
      this.dom.focus();
    }
  }, {
    key: "css",
    value: function css(value) {
      var _dom;

      return (_dom = this.dom).css.apply(_dom, arguments);
    }
  }, {
    key: "data",
    value: function data(params) {
      if (arguments.length === 1) {
        var _dom2;

        return (_dom2 = this.dom).data.apply(_dom2, arguments);
      } else {
        var _dom3;

        (_dom3 = this.dom).data.apply(_dom3, arguments);
        return this;
      }
    }
  }, {
    key: "lookup",
    value: function lookup(selector) {
      var dom = this.dom.find(selector).first();

      if (dom) {
        return Node.getFromDom(dom);
      }

      return null;
    }
  }, {
    key: "lookupAll",
    value: function lookupAll(selector) {
      var _this2 = this;

      var nodes = [];

      this.dom.find(selector).each(function () {
        nodes.push(Node.getFromDom(_this2));
      });

      return nodes;
    }
  }, {
    key: "toFront",
    value: function toFront() {
      var parent = this.parent;

      if (parent) {
        if (parent['childToFront']) {
          parent.childToFront(this);
        }
      }
    }
  }, {
    key: "toBack",
    value: function toBack() {
      var parent = this.parent;

      if (parent) {
        if (parent['childToBack']) {
          parent.childToBack(this);
        }
      }
    }
  }, {
    key: "free",
    value: function free() {
      var wrapperDom = this.dom.data('--wrapper-dom');

      if (wrapperDom) {
        wrapperDom.remove();
      } else {
        this.dom.detach();
      }

      return this;
    }
  }, {
    key: "show",
    value: function show() {
      var dom = this.dom;
      dom.css('display', '');

      if (this.dom.data('--wrapper-dom')) {
        dom = this.dom.data('--wrapper-dom');
        dom.css('display', '');
      }

      return this;
    }
  }, {
    key: "hide",
    value: function hide() {
      var dom = this.dom;
      dom.hide();

      if (this.dom.data('--wrapper-dom')) {
        dom = this.dom.data('--wrapper-dom');
        dom.hide();
      }

      return this;
    }
  }, {
    key: "toggle",
    value: function toggle() {
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

  }, {
    key: "animate",
    value: function animate(properties, options) {
      this.dom.animate(properties, options);
    }
  }, {
    key: "on",
    value: function on(event, callback) {
      var _this3 = this;

      this.dom.on(event, function (event) {
        event.sender = _this3;
        callback.call(_this3, event);
      });

      return this;
    }

    /**
     * @param {string} event
     * @returns {Node}
     */

  }, {
    key: "off",
    value: function off(event) {
      this.dom.off(event);
      return this;
    }

    /**
     * @param {string} event
     * @param params
     * @returns {*}
     */

  }, {
    key: "trigger",
    value: function trigger(event, params) {
      return this.dom.trigger(event, params);
    }

    /**
     * @param {string} id
     * @returns {Node}
     */

  }, {
    key: "child",
    value: function child(id) {
      return null;
    }

    /**
     * Returns inner Nodes as array.
     * @returns {Array}
     */

  }, {
    key: "innerNodes",
    value: function innerNodes() {
      return [];
    }

    /**
     * @param object
     */

  }, {
    key: "loadSchema",
    value: function loadSchema(object) {
      for (var prop in object) {
        if (object.hasOwnProperty(prop)) {
          if (prop[0] === '_') {
            continue;
          }

          var value = object[prop];

          if (value.hasOwnProperty('_')) {
            var uiLoader = new _UILoader2.default();
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
  }, {
    key: "uuid",
    get: function get() {
      return this.dom.attr('uuid');
    }

    /**
     * @param {string} value
     */
    ,
    set: function set(value) {
      this.dom.removeClass(this.uuid);
      this.dom.attr('uuid', value);
      this.dom.addClass(value);
    }
  }, {
    key: "id",
    get: function get() {
      return this.dom.attr('id');
    },
    set: function set(value) {
      this.__triggerPropertyChange('id', value);
      this.dom.attr('id', value);
    }

    /**
     * @returns {Array}
     */

  }, {
    key: "classes",
    get: function get() {
      return this.dom.data('custom-classes') || [];
    }

    /**
     * @param {Array} value
     */
    ,
    set: function set(value) {
      var oldClasses = this.classes;
      var classes = [];

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
  }, {
    key: "style",
    get: function get() {
      return this.dom.attr('style');
    },
    set: function set(value) {
      this.__triggerPropertyChange('style', value);
      this.dom.attr('style', value);
    }
  }, {
    key: "visible",
    get: function get() {
      var dom = this.dom;

      if (this.dom.data('--wrapper-dom')) {
        dom = this.dom.data('--wrapper-dom');
      }

      return dom.is(':visible');
    },
    set: function set(value) {
      this.__triggerPropertyChange('visible', value);

      if (value) {
        this.show();
      } else {
        this.hide();
      }
    }
  }, {
    key: "opacity",
    get: function get() {
      return this.dom.css('opacity');
    },
    set: function set(value) {
      this.__triggerPropertyChange('opacity', value);
      this.dom.css('opacity', value);
    }
  }, {
    key: "enabled",
    get: function get() {
      return !this.dom.prop("disabled");
    },
    set: function set(value) {
      this.__triggerPropertyChange('enabled', value);
      this.dom.prop('disabled', !value);
    }
  }, {
    key: "selectionEnabled",
    get: function get() {
      return this.dom.css('user-select') !== 'none';
    },
    set: function set(value) {
      this.dom.css('user-select', value ? '' : 'none');
    }
  }, {
    key: "focused",
    get: function get() {
      return this.dom.is(':focus');
    }
  }, {
    key: "x",
    get: function get() {
      return this.dom.position().left;
    },
    set: function set(value) {
      this.__triggerPropertyChange('x', value);
      this.dom.css({ left: value });
    }
  }, {
    key: "y",
    get: function get() {
      return this.dom.position().top;
    },
    set: function set(value) {
      this.__triggerPropertyChange('y', value);
      this.dom.css({ top: value });
    }
  }, {
    key: "position",
    get: function get() {
      return [this.x, this.y];
    },
    set: function set(value) {
      if (value instanceof Array && value.length >= 2) {
        this.x = value[0];
        this.y = value[1];
      }
    }
  }, {
    key: "width",
    get: function get() {
      return this.dom.width();
    },
    set: function set(value) {
      this.__triggerPropertyChange('width', value);
      this.dom.width(value);

      if (typeof value === 'string' && value.indexOf('%') > -1) {
        this.data('--width-percent', value);
      } else {
        this.data('--width-percent', null);
      }
    }
  }, {
    key: "height",
    get: function get() {
      return this.dom.height();
    },
    set: function set(value) {
      this.__triggerPropertyChange('height', value);
      this.dom.height(value);

      if (typeof value === 'string' && value.indexOf('%') > -1) {
        this.data('--height-percent', value);
      } else {
        this.data('--height-percent', null);
      }
    }
  }, {
    key: "size",
    get: function get() {
      return [this.width, this.height];
    },
    set: function set(value) {
      if (value instanceof Array && value.length >= 2) {
        this.width = value[0];
        this.height = value[1];
      }
    }
  }, {
    key: "tooltip",
    get: function get() {
      return this.dom.data('tooltip');
    },
    set: function set(tooltip) {
      if (this.tooltip === tooltip) return;
      this.__triggerPropertyChange('tooltip', tooltip);

      this.__setTooltip(tooltip);
    }
  }, {
    key: "tooltipOptions",
    get: function get() {
      return this.dom.data('--tooltipOptions') || {};
    },
    set: function set(options) {
      this.__triggerPropertyChange('tooltipOptions', options || {});

      this.dom.data('--tooltipOptions', options || {});
      this.__setTooltip(this.tooltip);
    }
  }, {
    key: "padding",
    get: function get() {
      return [_Utils2.default.toPt(this.dom.css('padding-top')), _Utils2.default.toPt(this.dom.css('padding-right')), _Utils2.default.toPt(this.dom.css('padding-bottom')), _Utils2.default.toPt(this.dom.css('padding-left'))];
    },
    set: function set(value) {
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
  }, {
    key: "parent",
    get: function get() {
      var parent = null;

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
  }, {
    key: "userData",
    get: function get() {
      return this.dom.data('--user-data');
    },
    set: function set(value) {
      this.__triggerPropertyChange('userData', value);
      this.dom.data('--user-data', value);
    }
  }], [{
    key: "getFromDom",
    value: function getFromDom(jqueryObject) {
      if (jqueryObject === null || jqueryObject.length === 0) {
        return null;
      }

      if (jqueryObject instanceof jQuery) {
        var wrapper = jqueryObject.data('--wrapper');
        return wrapper ? wrapper : new Node(jqueryObject);
      }

      throw new Error("Node.getFromDom(): 1 argument must be an jQuery object");
    }
  }]);

  return Node;
}();

exports.default = Node;

},{"../NX/UILoader":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\NX\\UILoader.js","./util/Utils":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\util\\Utils.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\PasswordField.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _TextInputControl2 = require('./TextInputControl');

var _TextInputControl3 = _interopRequireDefault(_TextInputControl2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var PasswordField = function (_TextInputControl) {
  _inherits(PasswordField, _TextInputControl);

  function PasswordField() {
    _classCallCheck(this, PasswordField);

    return _possibleConstructorReturn(this, (PasswordField.__proto__ || Object.getPrototypeOf(PasswordField)).apply(this, arguments));
  }

  _createClass(PasswordField, [{
    key: 'createDom',
    value: function createDom() {
      var dom = jQuery('<input type="password" class="form-control ux-text-input-control ux-password-field" />');
      return dom;
    }
  }]);

  return PasswordField;
}(_TextInputControl3.default);

exports.default = PasswordField;

},{"./TextInputControl":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\TextInputControl.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\ProgressBar.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Node2 = require('./Node');

var _Node3 = _interopRequireDefault(_Node2);

var _Utils = require('./util/Utils');

var _Utils2 = _interopRequireDefault(_Utils);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ProgressBar = function (_Node) {
  _inherits(ProgressBar, _Node);

  function ProgressBar() {
    _classCallCheck(this, ProgressBar);

    return _possibleConstructorReturn(this, (ProgressBar.__proto__ || Object.getPrototypeOf(ProgressBar)).apply(this, arguments));
  }

  _createClass(ProgressBar, [{
    key: 'createDom',
    value: function createDom() {
      var dom = jQuery('<div class="progress ux-progress-bar"><div class="progress-bar" role="progressbar"></div></div>');

      return dom;
    }
  }, {
    key: 'progress',
    get: function get() {
      return _Utils2.default.toPt(this.dom.find('> .progress-bar').css('width'));
    },
    set: function set(value) {
      this.dom.find('> .progress-bar').css('width', value + '%');
    }
  }, {
    key: 'kind',
    get: function get() {
      var dom = this.dom.find('> .progress-bar');

      if (dom.hasClass('progress-bar-success')) {
        return 'success';
      } else if (dom.hasClass('progress-bar-info')) {
        return 'info';
      } else if (dom.hasClass('progress-bar-warning')) {
        return 'warning';
      } else if (dom.hasClass('progress-bar-danger')) {
        return 'danger';
      }

      return 'default';
    },
    set: function set(value) {
      var dom = this.dom.find('> .progress-bar');

      dom.removeClass('progress-bar-' + this.kind);
      dom.addClass('progress-bar-' + value);
    }
  }, {
    key: 'animated',
    get: function get() {
      var dom = this.dom.find('> .progress-bar');
      return dom.hasClass('active');
    },
    set: function set(value) {
      var dom = this.dom.find('> .progress-bar');

      if (value) {
        dom.addClass('active');
      } else {
        dom.removeClass('active');
      }
    }
  }, {
    key: 'striped',
    get: function get() {
      var dom = this.dom.find('> .progress-bar');
      return dom.hasClass('progress-bar-striped');
    },
    set: function set(value) {
      var dom = this.dom.find('> .progress-bar');

      if (value) {
        dom.addClass('progress-bar-striped');
      } else {
        dom.removeClass('progress-bar-striped');
      }
    }
  }, {
    key: 'value',
    get: function get() {
      var width = this.dom.find('> .progress-bar').css('width');

      if (!width) {
        return 0;
      }

      return parseInt(width);
    },
    set: function set(v) {
      this.dom.find('> .progress-bar').css('width', v + "%");
    }
  }]);

  return ProgressBar;
}(_Node3.default);

exports.default = ProgressBar;

},{"./Node":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Node.js","./util/Utils":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\util\\Utils.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\SelectControl.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Node2 = require('./Node');

var _Node3 = _interopRequireDefault(_Node2);

var _AppMediator = require('../NX/AppMediator');

var _AppMediator2 = _interopRequireDefault(_AppMediator);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var SelectControl = function (_Node) {
  _inherits(SelectControl, _Node);

  function SelectControl(items) {
    _classCallCheck(this, SelectControl);

    var _this = _possibleConstructorReturn(this, (SelectControl.__proto__ || Object.getPrototypeOf(SelectControl)).call(this));

    if (items) {
      _this.items = items;
    }

    _this.dom.on('change.SelectControl', function (e) {
      _AppMediator2.default.sendUserInput(_this, { selected: _this.selected, selectedText: _this.selectedText });
    });
    return _this;
  }

  _createClass(SelectControl, [{
    key: 'createDom',
    value: function createDom() {
      var dom = jQuery('<select class="form-control ux-select-control">');
      return dom;
    }
  }, {
    key: 'items',
    get: function get() {
      var result = {};

      this.dom.find('option').each(function () {
        result[this.attr('value')] = $(this).text();
      });

      return result;
    },
    set: function set(value) {
      this.dom.find('option').remove();

      for (var key in value) {
        if (value.hasOwnProperty(key)) {
          this.dom.append(jQuery('<option value=\'' + key + '\'>' + value[key] + '</option>'));
        }
      }
    }
  }, {
    key: 'selected',
    get: function get() {
      return this.dom.val();
    },
    set: function set(value) {
      this.dom.val(value);
    }
  }, {
    key: 'selectedText',
    get: function get() {
      return this.dom.find('option:selected').text();
    },
    set: function set(value) {
      this.selected = null;

      this.dom.find('option').each(function () {
        if (jQuery(this).text() === value) {
          jQuery(this).prop('selected', true);
          return false;
        }
      });
    }
  }]);

  return SelectControl;
}(_Node3.default);

exports.default = SelectControl;

},{"../NX/AppMediator":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\NX\\AppMediator.js","./Node":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Node.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\TextArea.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _TextInputControl2 = require('./TextInputControl');

var _TextInputControl3 = _interopRequireDefault(_TextInputControl2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var TextArea = function (_TextInputControl) {
  _inherits(TextArea, _TextInputControl);

  function TextArea() {
    _classCallCheck(this, TextArea);

    return _possibleConstructorReturn(this, (TextArea.__proto__ || Object.getPrototypeOf(TextArea)).apply(this, arguments));
  }

  _createClass(TextArea, [{
    key: 'createDom',
    value: function createDom() {
      var dom = jQuery('<textarea class="form-control ux-text-input-control ux-text-area" />');
      return dom;
    }
  }, {
    key: 'wrap',
    get: function get() {
      return this.dom.attr('wrap');
    },
    set: function set(value) {
      this.dom.attr('wrap', value);
    }
  }]);

  return TextArea;
}(_TextInputControl3.default);

exports.default = TextArea;

},{"./TextInputControl":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\TextInputControl.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\TextField.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _TextInputControl2 = require('./TextInputControl');

var _TextInputControl3 = _interopRequireDefault(_TextInputControl2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var TextField = function (_TextInputControl) {
  _inherits(TextField, _TextInputControl);

  function TextField() {
    _classCallCheck(this, TextField);

    return _possibleConstructorReturn(this, (TextField.__proto__ || Object.getPrototypeOf(TextField)).apply(this, arguments));
  }

  _createClass(TextField, [{
    key: 'createDom',
    value: function createDom() {
      var dom = jQuery('<input type="text" class="form-control ux-text-input-control ux-text-field" />');
      return dom;
    }
  }]);

  return TextField;
}(_TextInputControl3.default);

exports.default = TextField;

},{"./TextInputControl":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\TextInputControl.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\TextInputControl.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Node2 = require('./Node');

var _Node3 = _interopRequireDefault(_Node2);

var _AppMediator = require('../NX/AppMediator');

var _AppMediator2 = _interopRequireDefault(_AppMediator);

var _Font = require('./paint/Font');

var _Font2 = _interopRequireDefault(_Font);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var TextInputControl = function (_Node) {
  _inherits(TextInputControl, _Node);

  function TextInputControl() {
    _classCallCheck(this, TextInputControl);

    var _this = _possibleConstructorReturn(this, (TextInputControl.__proto__ || Object.getPrototypeOf(TextInputControl)).call(this));

    _this.dom.on('keydown.TextInputControl', function (e) {
      _AppMediator2.default.sendUserInput(_this, function () {
        return { text: _this.text };
      });
    });
    return _this;
  }

  _createClass(TextInputControl, [{
    key: 'placeholder',
    get: function get() {
      return this.dom.attr('placeholder');
    },
    set: function set(value) {
      this.dom.attr('placeholder', value);
    }
  }, {
    key: 'editable',
    get: function get() {
      return !this.dom.prop('readonly');
    },
    set: function set(value) {
      this.dom.prop('readonly', !value);
    }
  }, {
    key: 'textAlign',
    get: function get() {
      return thid.dom.css('text-algin');
    },
    set: function set(value) {
      this.dom.css('text-algin', value);
    }
  }, {
    key: 'font',
    get: function get() {
      return _Font2.default.getFromDom(this.dom);
    },
    set: function set(value) {
      _Font2.default.applyToDom(this.dom, value);
    }
  }, {
    key: 'text',
    get: function get() {
      return this.dom.val();
    },
    set: function set(value) {
      this.dom.val(value);
    }
  }]);

  return TextInputControl;
}(_Node3.default);

exports.default = TextInputControl;

},{"../NX/AppMediator":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\NX\\AppMediator.js","./Node":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Node.js","./paint/Font":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\paint\\Font.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\ToggleButton.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

var _Button2 = require('./Button');

var _Button3 = _interopRequireDefault(_Button2);

var _AppMediator = require('../NX/AppMediator');

var _AppMediator2 = _interopRequireDefault(_AppMediator);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ToggleButton = function (_Button) {
  _inherits(ToggleButton, _Button);

  function ToggleButton(text, graphic) {
    _classCallCheck(this, ToggleButton);

    var _this = _possibleConstructorReturn(this, (ToggleButton.__proto__ || Object.getPrototypeOf(ToggleButton)).call(this, text, graphic));

    _this.dom.on('click.ToggleButton', function () {
      _AppMediator2.default.sendUserInput(_this, function () {
        return { 'selected': _this.selected };
      });
    });
    return _this;
  }

  _createClass(ToggleButton, [{
    key: 'createDom',
    value: function createDom() {
      var dom = _get(ToggleButton.prototype.__proto__ || Object.getPrototypeOf(ToggleButton.prototype), 'createDom', this).call(this);
      dom.addClass('ux-toggle-button');
      dom.attr('data-toggle', 'button');
      return dom;
    }
  }, {
    key: 'selected',
    get: function get() {
      return this.dom.hasClass('active');
    },
    set: function set(value) {
      if (value) {
        this.dom.addClass('active');
        this.dom.attr('aria-pressed', true);
      } else {
        this.dom.removeClass('active');
        this.dom.attr('aria-pressed', false);
      }
    }
  }]);

  return ToggleButton;
}(_Button3.default);

exports.default = ToggleButton;

},{"../NX/AppMediator":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\NX\\AppMediator.js","./Button":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Button.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\UX.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _Node = require('./Node');

var _Node2 = _interopRequireDefault(_Node);

var _Button = require('./Button');

var _Button2 = _interopRequireDefault(_Button);

var _ToggleButton = require('./ToggleButton');

var _ToggleButton2 = _interopRequireDefault(_ToggleButton);

var _Labeled = require('./Labeled');

var _Labeled2 = _interopRequireDefault(_Labeled);

var _Label = require('./Label');

var _Label2 = _interopRequireDefault(_Label);

var _Checkbox = require('./Checkbox');

var _Checkbox2 = _interopRequireDefault(_Checkbox);

var _Combobox = require('./Combobox');

var _Combobox2 = _interopRequireDefault(_Combobox);

var _Listbox = require('./Listbox');

var _Listbox2 = _interopRequireDefault(_Listbox);

var _ProgressBar = require('./ProgressBar');

var _ProgressBar2 = _interopRequireDefault(_ProgressBar);

var _Container = require('./Container');

var _Container2 = _interopRequireDefault(_Container);

var _HBox = require('./HBox');

var _HBox2 = _interopRequireDefault(_HBox);

var _VBox = require('./VBox');

var _VBox2 = _interopRequireDefault(_VBox);

var _ListView = require('./ListView');

var _ListView2 = _interopRequireDefault(_ListView);

var _AnchorPane = require('./AnchorPane');

var _AnchorPane2 = _interopRequireDefault(_AnchorPane);

var _ImageView = require('./ImageView');

var _ImageView2 = _interopRequireDefault(_ImageView);

var _TextInputControl = require('./TextInputControl');

var _TextInputControl2 = _interopRequireDefault(_TextInputControl);

var _TextField = require('./TextField');

var _TextField2 = _interopRequireDefault(_TextField);

var _PasswordField = require('./PasswordField');

var _PasswordField2 = _interopRequireDefault(_PasswordField);

var _TextArea = require('./TextArea');

var _TextArea2 = _interopRequireDefault(_TextArea);

var _Window = require('./Window');

var _Window2 = _interopRequireDefault(_Window);

var _Icon = require('./Icon');

var _Icon2 = _interopRequireDefault(_Icon);

var _Hyperlink = require('./Hyperlink');

var _Hyperlink2 = _interopRequireDefault(_Hyperlink);

var _Font = require('./paint/Font');

var _Font2 = _interopRequireDefault(_Font);

var _Utils = require('./util/Utils');

var _Utils2 = _interopRequireDefault(_Utils);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
  Node: _Node2.default, Window: _Window2.default,
  ImageView: _ImageView2.default,
  Button: _Button2.default, ToggleButton: _ToggleButton2.default,
  Labeled: _Labeled2.default,
  Label: _Label2.default, Checkbox: _Checkbox2.default, Combobox: _Combobox2.default, Listbox: _Listbox2.default, ProgressBar: _ProgressBar2.default,
  TextInputControl: _TextInputControl2.default, TextField: _TextField2.default, PasswordField: _PasswordField2.default, TextArea: _TextArea2.default,
  Container: _Container2.default, HBox: _HBox2.default, VBox: _VBox2.default, AnchorPane: _AnchorPane2.default, ListView: _ListView2.default, Icon: _Icon2.default, Hyperlink: _Hyperlink2.default,

  Font: _Font2.default
};

},{"./AnchorPane":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\AnchorPane.js","./Button":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Button.js","./Checkbox":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Checkbox.js","./Combobox":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Combobox.js","./Container":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Container.js","./HBox":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\HBox.js","./Hyperlink":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Hyperlink.js","./Icon":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Icon.js","./ImageView":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\ImageView.js","./Label":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Label.js","./Labeled":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Labeled.js","./ListView":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\ListView.js","./Listbox":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Listbox.js","./Node":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Node.js","./PasswordField":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\PasswordField.js","./ProgressBar":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\ProgressBar.js","./TextArea":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\TextArea.js","./TextField":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\TextField.js","./TextInputControl":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\TextInputControl.js","./ToggleButton":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\ToggleButton.js","./VBox":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\VBox.js","./Window":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Window.js","./paint/Font":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\paint\\Font.js","./util/Utils":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\util\\Utils.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\VBox.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

var _Container2 = require('./Container');

var _Container3 = _interopRequireDefault(_Container2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var VBox = function (_Container) {
  _inherits(VBox, _Container);

  function VBox(nodes) {
    _classCallCheck(this, VBox);

    var _this = _possibleConstructorReturn(this, (VBox.__proto__ || Object.getPrototypeOf(VBox)).apply(this, arguments));

    _this.spacing = 0;
    _this.align = ['top', 'left'];
    return _this;
  }

  _createClass(VBox, [{
    key: 'createDom',
    value: function createDom() {
      var dom = _get(VBox.prototype.__proto__ || Object.getPrototypeOf(VBox.prototype), 'createDom', this).call(this);
      dom.addClass('ux-v-box');
      return dom;
    }
  }, {
    key: 'createSlotDom',
    value: function createSlotDom(object) {
      var dom = _get(VBox.prototype.__proto__ || Object.getPrototypeOf(VBox.prototype), 'createSlotDom', this).call(this, object);

      if (this.spacing !== 0) {
        var slots = this.dom.find('> div');
        slots.last().css('margin-bottom', this.spacing);
        dom.css('margin-bottom', 0);
      }

      return dom;
    }
  }, {
    key: 'fitWidth',
    get: function get() {
      return this.dom.hasClass('ux-m-fit-width');
    },
    set: function set(value) {
      if (value) {
        this.dom.addClass('ux-m-fit-width');
      } else {
        this.dom.removeClass('ux-m-fit-width');
      }
    }
  }, {
    key: 'spacing',
    get: function get() {
      return this._spacing;
    },
    set: function set(value) {
      this._spacing = value;
      var slots = this.dom.find('> div');

      slots.css('margin-bottom', value + 'px');
      slots.last().css('margin-bottom', 0);
    }
  }]);

  return VBox;
}(_Container3.default);

exports.default = VBox;

},{"./Container":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Container.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Window.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

var _Node = require('./Node');

var _Node2 = _interopRequireDefault(_Node);

var _Container2 = require('./Container');

var _Container3 = _interopRequireDefault(_Container2);

var _AppMediator = require('../NX/AppMediator');

var _AppMediator2 = _interopRequireDefault(_AppMediator);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Window = function (_Container) {
  _inherits(Window, _Container);

  function Window() {
    _classCallCheck(this, Window);

    var _this = _possibleConstructorReturn(this, (Window.__proto__ || Object.getPrototypeOf(Window)).call(this));

    _this.contentDom = _this.dom.find('.modal-body');
    _this.dom.modal({
      backdrop: 'static',
      keyboard: false
    });

    _this.dom.on('hide.bs.modal.Window', function () {
      var data = {
        visible: false,
        close: true
      };

      _AppMediator2.default.sendUserInput(_this, data);
    });
    return _this;
  }

  _createClass(Window, [{
    key: 'hide',
    value: function hide() {
      this.dom.modal('hide');
    }
  }, {
    key: 'show',
    value: function show() {
      this.dom.modal('show');
    }
  }, {
    key: 'free',
    value: function free() {
      var _this2 = this;

      this.hide();

      setTimeout(function () {
        _get(Window.prototype.__proto__ || Object.getPrototypeOf(Window.prototype), 'free', _this2).call(_this2);
      }, 3000);

      return this;
    }
  }, {
    key: 'createDom',
    value: function createDom() {
      var dom = jQuery('<div class="modal fade in ux-window ux-closable" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content">' + '<div class="modal-header">' + '<h5 class="modal-title ux-window-title"></h5>' + '<button type="button" class="close" data-dismiss="modal" aria-hidden="Close">×</button>' + '</div>' + '<div class="modal-body"></div>' + '<div class="modal-footer" style="display: none;"></div>' + '</div></div></div>');

      return dom;
    }
  }, {
    key: 'innerNodes',
    value: function innerNodes() {
      var result = _get(Window.prototype.__proto__ || Object.getPrototypeOf(Window.prototype), 'innerNodes', this).call(this);

      if (this.footer) {
        result.push(this.footer);
      }

      return result;
    }
  }, {
    key: 'centered',
    get: function get() {
      return this.dom.hasClass('ux-centered');
    },
    set: function set(value) {
      if (value) {
        this.dom.addClass('ux-centered');
      } else {
        this.dom.removeClass('ux-centered');
      }
    }
  }, {
    key: 'closable',
    get: function get() {
      return this.dom.hasClass('ux-closable');
    },
    set: function set(value) {
      if (value) {
        this.dom.addClass('ux-closable');
      } else {
        this.dom.removeClass('ux-closable');
      }
    }
  }, {
    key: 'title',
    get: function get() {
      return this.dom.find('.ux-window-title').text();
    },
    set: function set(value) {
      return this.dom.find('.ux-window-title').text(value);
    }
  }, {
    key: 'width',
    get: function get() {
      return this.dom.find('.modal-dialog').width();
    },
    set: function set(value) {
      this.__triggerPropertyChange('width', value);
      this.dom.find('.modal-dialog').width(value);
    }
  }, {
    key: 'height',
    get: function get() {
      return this.dom.find('.modal-dialog').height();
    },
    set: function set(value) {
      this.__triggerPropertyChange('height', value);
      this.dom.find('.modal-dialog').height(value);
    }
  }, {
    key: 'footer',
    get: function get() {
      var footer = this.dom.find('.modal-footer > *').first();
      return _Node2.default.getFromDom(footer);
    },
    set: function set(value) {
      var footer = this.dom.find('.modal-footer');
      footer.empty();

      if (value instanceof _Node2.default) {
        footer.append(value.dom);
        footer.show();
      } else if (value === null) {
        footer.hide();
      } else {
        throw new Error("footer value must be Node or null, passed " + (typeof value === 'undefined' ? 'undefined' : _typeof(value)));
      }
    }
  }]);

  return Window;
}(_Container3.default);

exports.default = Window;

},{"../NX/AppMediator":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\NX\\AppMediator.js","./Container":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Container.js","./Node":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\Node.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\paint\\Font.js":[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _Utils = require('./../util/Utils');

var _Utils2 = _interopRequireDefault(_Utils);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Font = function () {
  function Font(name, size) {
    _classCallCheck(this, Font);

    this._dom = null;
    this.name = name || 'serif';
    this.size = size || 12;
  }

  _createClass(Font, [{
    key: 'name',
    get: function get() {
      return this._name;
    },
    set: function set(value) {
      this._name = value;

      if (this._dom) {
        this._dom.css('font-family', value);
      }
    }
  }, {
    key: 'size',
    get: function get() {
      return this._size;
    },
    set: function set(value) {
      this._size = value | 0;

      if (this._dom) {
        this._dom.css('font-size', value + 'px');
      }
    }
  }, {
    key: 'bold',
    get: function get() {
      return this._bold | false;
    },
    set: function set(value) {
      this._bold = value | false;

      if (this._dom) {
        this._dom.css('font-weight', this._bold ? 'bold' : 'normal');
      }
    }
  }, {
    key: 'italic',
    get: function get() {
      return this._italic | false;
    },
    set: function set(value) {
      this._italic = value | false;

      if (this._dom) {
        this._dom.css('font-style', this._italic ? 'italic' : 'normal');
      }
    }
  }, {
    key: 'underline',
    get: function get() {
      return this._underline | false;
    },
    set: function set(value) {
      this._underline = value | false;

      if (this._underline) {
        this._linethrough = false;
      }

      if (this._dom) {
        this._dom.css('text-decoration', this._underline ? 'underline' : 'none');
      }
    }
  }, {
    key: 'linethrough',
    get: function get() {
      return this._linethrough | false;
    },
    set: function set(value) {
      this._linethrough = value | false;

      if (this._linethrough) {
        this._underline = false;
      }

      if (this._dom) {
        this.dom.css('text-decoration', this._linethrough ? 'line-through' : 'none');
      }
    }
  }], [{
    key: 'applyToDom',
    value: function applyToDom(dom, font) {
      if (font instanceof Font) {
        dom.css('font-family', font.name);

        if (font.size) {
          dom.css('font-size', font.size);
        } else {
          dom.css('font-size', '');
        }

        if (font.bold) {
          dom.css('font-weight', 'bold');
        } else {
          dom.css('font-weight', '');
        }

        if (font.italic) {
          dom.css('font-style', 'italic');
        } else {
          dom.css('font-style', '');
        }

        if (font.underline) {
          dom.css('text-decoration', 'underline');
        } else if (font.linethrough) {
          dom.css('text-decoration', 'line-through');
        } else {
          dom.css('text-decoration', '');
        }
      } else if ((typeof font === 'undefined' ? 'undefined' : _typeof(font)) === 'object') {
        if (font.hasOwnProperty('name')) {
          if (font.name) {
            dom.css('font-family', font.name);
          } else {
            dom.css('font-family', '');
          }
        }

        if (font.hasOwnProperty('size')) {
          if (font.size) {
            dom.css('font-size', font.size);
          } else {
            dom.css('font-size', '');
          }
        }

        if (font.hasOwnProperty('bold')) {
          if (font.bold) {
            dom.css('font-weight', 'bold');
          } else {
            dom.css('font-weight', '');
          }
        }

        if (font.hasOwnProperty('italic')) {
          if (font.italic) {
            dom.css('font-style', 'italic');
          } else {
            dom.css('font-style', '');
          }
        }

        if (font.hasOwnProperty('underline') && font.underline) {
          dom.css('text-decoration', 'underline');
        } else if (font.hasOwnProperty('linethrough') && font.linethrough) {
          dom.css('text-decoration', 'line-through');
        } else {
          dom.css('text-decoration', '');
        }
      }
    }
  }, {
    key: 'getFromDom',
    value: function getFromDom(dom) {
      if (dom instanceof jQuery) {
        var family = dom.css('font-family');
        var size = _Utils2.default.toPt(dom.css('font-size'));

        var bold = dom.css('font-weight') === 'bold';
        var italic = dom.css('font-style') === 'italic';
        var linethrough = dom.css('text-decoration') === 'line-through';
        var underline = dom.css('text-decoration') === 'underline';

        var font = new Font(family, size);

        if (bold) font.bold = true;
        if (italic) font.italic = true;
        if (underline) font.underline = true;
        if (linethrough) font.linethrough = true;

        font._dom = dom;
        return font;
      }

      throw new TypeError('getFromDom(): 1 argument must be jquery object');
    }
  }]);

  return Font;
}();

exports.default = Font;

},{"./../util/Utils":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\util\\Utils.js"}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\util\\Utils.js":[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Utils = function () {
  function Utils() {
    _classCallCheck(this, Utils);
  }

  _createClass(Utils, null, [{
    key: "isElement",
    value: function isElement(obj) {
      try {
        //Using W3 DOM2 (works for FF, Opera and Chrom)
        return obj instanceof HTMLElement;
      } catch (e) {
        //Browsers not supporting W3 DOM2 don't have HTMLElement and
        //an exception is thrown and we end up here. Testing some
        //properties that all elements have. (works on IE7)
        return (typeof obj === "undefined" ? "undefined" : _typeof(obj)) === "object" && obj.nodeType === 1 && _typeof(obj.style) === "object" && _typeof(obj.ownerDocument) === "object";
      }
    }
  }, {
    key: "toPt",
    value: function toPt(cssValue) {
      return parseInt(cssValue);
    }
  }]);

  return Utils;
}();

exports.default = Utils;

},{}],"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\lib.js":[function(require,module,exports){
'use strict';

var _NX = require('./NX/NX');

var _NX2 = _interopRequireDefault(_NX);

var _UX = require('./UX/UX');

var _UX2 = _interopRequireDefault(_UX);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

window.NX = _NX2.default;
window.UX = _UX2.default;

},{"./NX/NX":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\NX\\NX.js","./UX/UX":"D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\UX\\UX.js"}]},{},["D:\\dev\\personal\\framework\\web-ui\\src-js\\src\\lib.js"])

//# sourceMappingURL=dnext-engine.js.map
