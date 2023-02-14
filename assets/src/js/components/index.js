import forms from './forms';
import accordion from './accordion';
import tooltips from './tooltips';

export default {
    init() {
        forms();
        accordion();
        tooltips();
		console.log("components scripts loaded");
    },
    finalize() {}
}
