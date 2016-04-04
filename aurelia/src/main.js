import {Router} from 'aurelia-router';

export class Main {

    static inject() { return [Router]; }

    constructor(router) {
        this.theRouter = router;
        this.theRouter.navigate("login");
    }

}
