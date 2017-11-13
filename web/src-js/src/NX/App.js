import Logger from './Logger';

class App {
  constructor() {
    this.logger = new Logger();
  }

  log(message) {
    this.logger.info(message);
  }
}

export default App;
