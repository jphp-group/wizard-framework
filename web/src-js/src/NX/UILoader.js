import Node from './../UX/Node';
import Container from './../UX/Container';
import UX from './../UX/UX';

class UILoader {

  linkToMediator(node, data, uiMediator) {
    if (uiMediator !== undefined) {
      const watchedEvents = data['_watchedEvents'];
      if (watchedEvents !== undefined) {
        for (let watchedEvent of watchedEvents) {

          node.on(watchedEvent, (e) => {
            uiMediator.triggerEvent(node, watchedEvent, e);
          })
        }
      }
    }
  }

  /**
   * @param object
   * @param {UIMediator} uiMediator
   * @returns {Node}
   */
  load(object, uiMediator) {
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

        this.linkToMediator(node, object, uiMediator);
        node.loadSchema(object);
        return node;
      } else {
        throw new Error(`Type '${type}' is not UI component class`);
      }
    }
  }

    /**
     * @param {string} jsonString
     * @param {UIMediator} uiMediator
     * @returns {Node}
     */
  loadFromJson(jsonString, uiMediator) {
    return this.load(JSON.parse(jsonString), uiMediator);
  }

    /**
     *
     * @param {string} urlToJson
     * @param {UIMediator} uiMediator
     * @param {function} callback
     */
  loadFromUrl(urlToJson, uiMediator, callback) {
    jQuery.getJSON(urlToJson, (data) => {
        callback(this.load(data, uiMediator));
    });
  }
}

export default UILoader;
