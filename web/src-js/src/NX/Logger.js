

class Logger {
  static get DEBUG() { return 1 }
  static get INFO() { return 10 }
  static get WARNING() { return 100 }
  static get ERROR() { return 1000 }

  constructor() {
    this.level = Logger.INFO;
  }

  debug(message) {
    if (this.level >= Logger.DEBUG) {
      console.debug(...arguments);
    }
  }

  info(message) {
    if (this.level >= Logger.INFO) {
      console.info(...arguments);
    }
  }

  error(message) {
    if (this.level >= Logger.ERROR) {
      console.error(...arguments);
    }
  }

  warn(message) {
    if (this.level >= Logger.WARNING) {
      console.warn(message);
    }
  }
}

export default Logger;
