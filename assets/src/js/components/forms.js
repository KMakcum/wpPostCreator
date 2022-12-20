import IMask from 'imask';
import datepicker from 'js-datepicker'
import moment from 'moment';

export default function() {
    const phoneInputs = document.querySelectorAll('input[name="phone"]');
    const dateInputs = document.querySelectorAll('input[name="post_date"]');

    datepicker('input[name="post_date"]', {
        formatter: (input, date, instance) => {
            const d = new Date();
            input.value = d.toLocaleString("sv-SE")
        }
    });

    phoneInputs && phoneInputs.forEach(input => {
        IMask(input, {
            mask: '+{7}(000)000-00-00'
        });
    });

    //todo: формат даты при смене в датапикере
    dateInputs && dateInputs.forEach(input => {
        let momentFormat = 'YYYY-MM-DD HH:mm:ss';
        IMask(input, {
            mask: Date,
            pattern: momentFormat,
            lazy: false,
            min: new Date(1970, 0, 1),
            max: new Date(2030, 0, 1),

            format: function (date) {
                return moment(date).format(momentFormat);
            },
            parse: function (str) {
                return moment(str, momentFormat);
            },

            blocks: {
                YYYY: {
                    mask: IMask.MaskedRange,
                    from: 1970,
                    to: 2030
                },
                MM: {
                    mask: IMask.MaskedRange,
                    from: 1,
                    to: 12
                },
                DD: {
                    mask: IMask.MaskedRange,
                    from: 1,
                    to: 31
                },
                HH: {
                    mask: IMask.MaskedRange,
                    from: 0,
                    to: 23
                },
                mm: {
                    mask: IMask.MaskedRange,
                    from: 0,
                    to: 59
                },
                ss: {
                    mask: IMask.MaskedRange,
                    from: 0,
                    to: 59
                }
            }
        });
    });

    const formSetBtns = document.querySelectorAll('.form_section__btn');
    formSetBtns && formSetBtns.forEach(btn => {
        btn.addEventListener('click', e => {
            const target = e.target,
                targetSettings = target.dataset.settings;

            switch (targetSettings) {
                case 'default':
                    defaultFields();
                    break;
                case 'clear':
                    clearFields();
                    break;
                default:
                    console.log('Выбрана несуществующая кнопка');
            }
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

    function defaultFields() {

        const defaultArr = new Map( [
            ['post_title', 'Заголовок записи'],
            // ['post_date', datePub],
            ['post_date', getDateFormat()],
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
    }

    function clearFields() {
        let inputs = document.querySelectorAll('#main_form input, #main_form textarea');
        inputs.forEach(input => {
            input.value = '';
        });
    }

    function getDateFormat() {
        let date = new Date(),
            year = date.getFullYear(),
            month = date.getMonth() + 1,
            day = date.getDate(),
            hours = date.getHours(),
            minutes = date.getMinutes(),
            seconds = (date.getSeconds() >=0 && date.getSeconds() <=9) ? '0'+date.getSeconds() : date.getSeconds();

        //todo: разобраться с форматом даты. Есть проблемы с числавми одной цифры

        // let d = year+'-'+month+'-'+day+' '+hours+':'+minutes+':'+seconds;

        return date.toLocaleString("sv-SE");
    }
}
