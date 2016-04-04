import { Router } from "aurelia-router";
import $ from 'bootstrap'

export class App {
    static inject() { return [Router]; }

    constructor(router) {
        this.router = router;
        this.router.configure(config => {
            config.title = "Syoracle";

            config.map([
                { route: ['', 'main'], name: 'main', moduleId: './main', nav: true, title: 'Main Page' },
                { route: 'login', name: 'Login', moduleId: './login', nav: true, title: 'Login' }
            ]);
        });
    }

}
