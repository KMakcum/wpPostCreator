export default function(){
    const body = document.documentElement;
    const navBtn = document.querySelector('.hamburger');
    const navBackdrop = document.querySelector('.nav-backdrop');
    const menuClass = 'show-nav';

    navBtn && navBtn.addEventListener('click', () => {
        body.classList.toggle(menuClass);
        bodyChangeFix();
    });

    navBackdrop && navBackdrop.addEventListener('click', () => {
        body.classList.remove(menuClass);
        bodyChangeFix();
    });

    function bodyChangeFix() {
        if (body.classList.contains('menuClass')) {
            bodyFixPosition();
        } else {
            bodyUnfixPosition();
        }
    }

    // Fix body IOS
    function bodyFixPosition() {
        setTimeout( function() {
            if ( !document.body.hasAttribute('data-body-scroll-fix') ) {
                let scrollPosition = window.pageYOffset || body.scrollTop;
                document.body.setAttribute('data-body-scroll-fix', scrollPosition);
                document.body.style.overflow = 'hidden';
                document.body.style.position = 'fixed';
                document.body.style.left = '0';
                document.body.style.width = '100%';
            }
        }, 500 );
    }

    // Unfix body IOS
    function bodyUnfixPosition() {
        if ( document.body.hasAttribute('data-body-scroll-fix') ) {
            let scrollPosition = document.body.getAttribute('data-body-scroll-fix');
            document.body.style.overflow = '';
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.left = '';
            document.body.style.width = '';
            window.scroll(0, scrollPosition);

            setTimeout(() => {
                document.body.removeAttribute('data-body-scroll-fix');
            }, 500)
        }
    }
}
