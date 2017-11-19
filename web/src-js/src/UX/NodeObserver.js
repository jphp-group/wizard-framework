

class NodeObserver {
    /**
     * @type {Node}
     * @private
     */
    __node = null;

    /**
     * @param {Node} node
     */
    constructor(node) {
        this.__node = node;
        node.__observers.push(this);
    }

    triggerPropertyChange(name, value) {

    }
}