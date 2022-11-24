import navMenu from './nav-menu';
import pageScroll from './page-scroll';
import anchorScroll from './anchor-scroll';
import forms from './forms';
import textarea from './textarea';
import wow from './wow';
import sliders from './sliders';

export default {
    init() {
        navMenu();
        pageScroll();
        anchorScroll();
        forms();
        textarea();
        wow();
        sliders();
		console.log("components scripts loaded");
    },
    finalize() {}
}
