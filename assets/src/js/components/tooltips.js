import tippy from 'tippy.js';

export default function() {
    tippy('[data-tippy-content]', {
        placement: 'right',
        // arrow: false,
        animation: 'shift-away',
        theme: 'light',
        // interactive: true,
    });
    // tippy('[data-tippy-content]');

}
