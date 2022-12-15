export default function(){
    const singleSomeLinks  = document.querySelectorAll('.accordion__link--single');
    const someLinks        = document.querySelectorAll('.accordion__link');
    const somePanels       = document.querySelectorAll('.accordion__panel');

// If link has class 'active', panel is open
    for (let i = 0; i < someLinks.length; i++) {
        if ( someLinks[i].classList.contains('active') ) {
            const panel = someLinks[i].nextElementSibling;
            panel.style.maxHeight = panel.scrollHeight + "px";
        }
    }
    someLinks && someLinks.forEach(link => {
        link.addEventListener('click', e => {
            accordion(e, link, someLinks, somePanels);
        });
    });


    singleSomeLinks && singleSomeLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            accordionSingle(link);
        });
    });

    function accordion(e, target, links, panels) {
        e.preventDefault();
        const panel = target.nextElementSibling;
        const open = target.getAttribute('data-open')

        if (open !== 'single') {
            for (let i = 0; i < links.length; i++) {
                !links[i].contains(e.target) && links[i].classList.remove('active');
            }

            for (let i = 0; i < panels.length; i++) {
                !panels[i].contains(panel) && panels[i].removeAttribute('style');
            }
        }

        togglePanel(target, panel);
    }

    function accordionSingle(link) {
        const openId = link.getAttribute('data-link');
        const singleSomePanels = document.querySelectorAll('[data-panel="'+openId+'"]');

        singleSomePanels && singleSomePanels.forEach(panel => {
            togglePanel(link, panel)
        });
    }


    function togglePanel(target, panel) {
        target.classList.toggle('active');

        if (panel.style.maxHeight) {
            panel.style.maxHeight = null;
        } else {
            panel.style.maxHeight = panel.scrollHeight + "px";
        }
    }
}
