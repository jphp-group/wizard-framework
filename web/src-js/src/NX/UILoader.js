import Node from './../UX/Node';
import Container from './../UX/Container';
import UX from './../UX/UX';

class UILoader {

  load(object, controller) {
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

          for (var i = 0; i < children.length; i++) {
            const child = this.load(children[i], controller);
            node.add(child);
          }
        }

        node.load(object, controller);

        return node;
      } else {
        throw new Error(`Type '${type}' is not UI component class`);
      }
    }
  }

  loadFromJson(jsonString, controller) {
    return this.load(JSON.parse(jsonString), controller);
  }

  loadFromUrl(urlToJson, callback, controller) {

    jQuery.getJSON(urlToJson, (data) => {
        callback(this.load(data, controller));
    });
  }
}

export default UILoader;
