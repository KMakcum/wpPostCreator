import navMenu from './nav-menu';
import pageScroll from './page-scroll';
import anchorScroll from './anchor-scroll';
import forms from './forms';
import sliders from './sliders';
import accordion from './accordion';

export default {
    init() {
        // navMenu();
        // pageScroll();
        // anchorScroll();
        forms();
        // sliders();
        accordion();
		console.log("components scripts loaded");
    },
    finalize() {}
}
