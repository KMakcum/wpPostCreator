import IMask from 'imask';

export default function() {
    const phoneInputs = document.querySelectorAll('input[name="phone"]');

    [...phoneInputs].forEach(input => {
        IMask(input, {
            mask: '+{7}(000)000-00-00'
        });
    })

    const formSetBtns = document.querySelectorAll('.form_section__btn');
    formSetBtns && formSetBtns.forEach(btn => {
        btn.addEventListener('click', e => {
            let date = new Date(),
                year = date.getFullYear(),
                month = date.getMonth(),
                day = date.getDate(),
                hours = date.getHours(),
                minutes = date.getMinutes(),
                seconds = (date.getSeconds() >=0 && date.getSeconds() <=9) ? '0'+date.getSeconds() : date.getSeconds();

            let datePub = year+'-'+month+'-'+day+' '+hours+':'+minutes+':'+seconds;

            const defaultArr = new Map( [
                ['post_title', 'Заголовок записи'],
                ['post_date', datePub],
                ['post_content', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum possimus quaerat quos tempora tenetur? Commodi dolore est incidunt natus necessitatibus odio quas sequi vero voluptatum! Aut consectetur cupiditate debitis deserunt dolor error fugiat harum hic id labore, molestiae nostrum obcaecati, pariatur quibusdam quidem soluta voluptatum. Blanditiis corporis fugit possimus quam?'],
                ['post_excerpt', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quaerat, quisquam.'],
                ['post_url', 'zagolovok_zapisi'],
                ['post_cat_name', 'Категория'],
                ['post_cat_url', 'kategoriya'],
            ]);

            defaultArr.forEach(function(value,key) {
                let input = document.querySelector('#'+key);
                input.value = value;
            });
        });
    })

    const radioInps = document.querySelectorAll('.switcher');
    radioInps && radioInps.forEach(radio => {
        radio.addEventListener('click', e => {
            let radioVal = radio.value;
            let hiddenBlocks = document.querySelectorAll('[data-radio="'+radioVal+'"]');

            if (hiddenBlocks.length) {
                hiddenBlocks.forEach(block => {
                    if (!radio.checked) {
                    } else {
                        block.style.maxHeight = block.scrollHeight + "px";
                    }
                });
            }
        });
    })


}
