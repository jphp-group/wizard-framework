

import Container from "../UX/Container";

class UIMediator
{
  /**
   * @param {Node} node
   */
  constructor() {
    this._callbacks = [];
  }

  /**
   * @param {Node} node
   * @param wsUrl
   * @param sessionId
   */
  startWatching(node, wsUrl, sessionId) {
    this.node = node;
    this.sessionId = sessionId;

    const loc = window.location;
    let newUri = '';

    if (loc.protocol === "https:") {
      newUri = "wss:";
    } else {
      newUri = "ws:";
    }

    newUri += "//" + loc.host;
    newUri += wsUrl;

    this.ws = new WebSocket(newUri);

    this.ws.onopen = () => {
      this.send('initialize', {sessionId});
    };

    this.ws.onmessage = (event) => {
      const message = JSON.parse(event.data);
      const type = message.type;

      switch (type) {
        case "ui-alert":
          this.triggerAlert(message);
          break;

        case "ui-set-property":
          this.triggerSetProperty(message);
          break;

        case "ui-call-method":
          this.triggerCallMethod(message);
          break;

        case "ui-event-link":
          this.triggerOnEventLink(message);
          break;
      }
    };
  }

  /**
   * @param type
   * @param message
   * @param callback
   * @returns {boolean}
   */
  sendIfCan(type, message, callback) {
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
  send(type, message, callback) {
    if (this.ws === undefined) {
      throw "Mediator is not in watching state.";
    }

    message.type = type;
    message.id = Math.random().toString(36).substring(7);
    message.sessionId = this.sessionId;

    if (callback) {
      this._callbacks[message.id] = callback;
    }

    console.info("UIMediator.send", message);

    this.ws.send(JSON.stringify(message));
  }

  findNodeByUuid(uuid, node) {
    if (uuid === node.uuid) {
      return node;
    }

    if (node instanceof Container) {
      let children = node.children();

      for (let i = 0; i < children.length; i++) {
        const found = this.findNodeByUuid(uuid, children[i]);

        if (found !== null) {
          return found;
        }
      }
    }

    return null;
  }

  triggerEvent(node, event, e) {
    this.sendIfCan('ui-trigger', {
      uuid: node.uuid,
      event: event
    });
  }

  triggerAlert(message) {
    const text = message['text'];
    alert(text);
  }

  triggerCallMethod(message) {
    const uuid = message['uuid'];
    const method = message['method'];
    const args = message['args'] || [];

    const node = this.findNodeByUuid(uuid, this.node);

    if (node !== null) {
      node[method].apply(node, args);
    } else {
      console.warn(`Failed to set property, node with uuid = ${uuid} is not found`);
    }
  }

  triggerSetProperty(message) {
    const uuid = message['uuid'];
    const property = message['property'];
    const value = message['value'];

    const node = this.findNodeByUuid(uuid, this.node);

    if (node !== null) {
      node[property] = value;
    } else {
      console.warn(`Failed to set property, node with uuid = ${uuid} is not found`);
    }
  }

  triggerOnEventLink(message) {
    const uuid = message['uuid'];
    const event = message['event'];

    const node = this.findNodeByUuid(uuid, this.node);

    if (node !== null) {
      node.off(event);

      node.on(event, (e) => {
        this.triggerEvent(node, event, e);
      })
    } else {
      console.warn(`Failed to link event ${event}, node with uuid = ${uuid} is not found`);
    }
  }
}

export default UIMediator;