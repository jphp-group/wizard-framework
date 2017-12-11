import Node from './../UX/Node';
import Container from './../UX/Container';
import UX from './../UX/UX';
import uiMediator from './UIMediator';

class UILoader {

  linkToMediator(node, data) {
    const watchedEvents = data['_watchedEvents'];
    if (watchedEvents !== undefined) {
      for (let watchedEvent of watchedEvents) {
        node.on(`${watchedEvent}.UIMediator`, (e) => {
          uiMediator.triggerEvent(node, watchedEvent, e);
        })
      }
    }
  }

  /**
   * @param object
   * @returns {Node}
   */
  load(object) {
    if (object && typeof object === "object") {
      const type = object['_'];

      if (!type) {
        throw new Error("Type is not defined in '_' property!");
      }

      const cls = UX[type];

      if (!cls) {
        throw new Error(`Type '${type}' is not defined`);
      }

      const node = new cls();

      if (node instanceof Node) {
        if (node instanceof Container && jQuery.isArray(object['_content'])) {
          const children = object['_content'];

          for (let i = 0; i < children.length; i++) {
            const child = this.load(children[i], uiMediator);
            node.add(child);
          }
        }

        node.loadSchema(object);
        this.linkToMediator(node, object);
        return node;
      } else {
        throw new Error(`Type '${type}' is not UI component class`);
      }
    }
  }

  /**
   * @param {string} jsonString
   * @returns {Node}
   */
  loadFromJson(jsonString) {
    return this.load(JSON.parse(jsonString));
  }

  /**
   *
   * @param {string} urlToJson
   * @param {function} callback
   */
  loadFromUrl(urlToJson, callback) {
    jQuery.getJSON(urlToJson, (data) => {
      callback(this.load(data));
    });
  }
}

export default UILoader;
