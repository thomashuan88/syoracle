
import $ from 'bootstrap'

export class App {
  configureRouter(config, router) {
    config.title = 'login';
    config.map([
      { route: 'login', name: 'Login', moduleId: './login', nav: true, title:'Login' }
    ]);

    this.router = router;
  }
}