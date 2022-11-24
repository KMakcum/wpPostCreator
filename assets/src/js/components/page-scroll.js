export default function(){
    const pageScroll = () => {
        document.documentElement.classList.toggle(
            'page-scrolled',
            window.scrollY > 50
        )
    }

    pageScroll();
    window.addEventListener('scroll', pageScroll);
}
