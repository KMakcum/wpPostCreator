import "./spec"
import "./fixes"

import Router from './utils/Router';
import components from './components';

const routes = new Router({
    components,
})

window.addEventListener("DOMContentLoaded", () => {
    routes.loadEvents();
    components.init();
})
