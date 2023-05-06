import IMask from 'imask';
import datepicker from 'js-datepicker'
// import moment from 'moment';

export default function() {
    const phoneInputs = document.querySelectorAll('input[name="phone"]');
    // const dateInputs = document.querySelectorAll('input[name="post_date"]');
    const dateInputs = false;

    dateInputs && datepicker('input[name="post_date"]', {
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

    // Заполнение всех полей кнопкой
    const formSetBtns = document.querySelectorAll('.form_section__btn');
    formSetBtns && formSetBtns.forEach(btn => {
        btn.addEventListener('click', e => {
            const target = e.target,
                targetSettings = target.dataset.settings;

            switch (targetSettings) {
                case 'default':
                    setDefaultFields();
                    break;
                case 'clear':
                    clearFields();
                    break;
                default:
                    console.log('Выбрана несуществующая кнопка');
            }
        });
    })

    // Скрытие/показ элемента по radio кнопки
    const radioInps = document.querySelectorAll('.switcher-select input[type="radio"]');
    radioInps && radioInps.forEach(radio => {
        radio.addEventListener('click', e => {
            let radioVal = radio.value;
            let allHiddenBlocks = document.querySelectorAll('[data-radio]');
            let hiddenBlocks = document.querySelectorAll('[data-radio="'+radioVal+'"]');

            if(allHiddenBlocks.length) {
                allHiddenBlocks.forEach(block => {
                    block.style.maxHeight = null;
                });
            }
            if (hiddenBlocks.length) {
                hiddenBlocks.forEach(block => {
                    if (radio.checked) {
                        block.style.maxHeight = block.scrollHeight + "px";
                    }
                });
            }
        });
    })

    const postTitleInput = document.querySelector('#post_title');
    postTitleInput.addEventListener('change', function () {
        updateURL(document.querySelector('#post_url'), this)
    });

    const catNameInput = document.querySelector('#post_cat_name');
    catNameInput.addEventListener('change', function () {
        updateURL(document.querySelector('#post_cat_url'), this)
    });

    function updateURL(urlField, nameField) {
        if (!urlField || !nameField) return false;
        const nameVal = nameField.value;

        urlField.value = translit(nameVal)
    }

    function getDefaultFieldsArray(type) {
        let defaultArr;
        const defaultArrPost = new Map( [
            ['post_title', 'Заголовок записи'],
            // ['post_date', datePub],
            ['post_date', getDateFormat()],
            ['post_content', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum possimus quaerat quos tempora tenetur? Commodi dolore est incidunt natus necessitatibus odio quas sequi vero voluptatum! Aut consectetur cupiditate debitis deserunt dolor error fugiat harum hic id labore, molestiae nostrum obcaecati, pariatur quibusdam quidem soluta voluptatum. Blanditiis corporis fugit possimus quam?'],
            ['post_excerpt', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quaerat, quisquam.'],
            ['post_url', 'zagolovok_zapisi'],
            ['post_cat_name', 'Категория'],
            ['post_cat_url', 'kategoriya'],
        ]);
        const defaultArrPage = new Map( [
            ['post_title', 'Заголовок страницы'],
            // ['post_date', datePub],
            ['post_date', getDateFormat()],
            ['post_content', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum possimus quaerat quos tempora tenetur? Commodi dolore est incidunt natus necessitatibus odio quas sequi vero voluptatum! Aut consectetur cupiditate debitis deserunt dolor error fugiat harum hic id labore, molestiae nostrum obcaecati, pariatur quibusdam quidem soluta voluptatum. Blanditiis corporis fugit possimus quam?'],
            ['post_excerpt', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quaerat, quisquam.'],
            ['post_url', 'zagolovok_stranicy'],
            ['post_cat_name', 'Категория'],
            ['post_cat_url', 'kategoriya'],
        ]);
        const defaultArrProd = new Map( [
            ['post_title', 'Заголовок товара'],
            // ['post_date', datePub],
            ['post_date', getDateFormat()],
            ['post_content', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum possimus quaerat quos tempora tenetur? Commodi dolore est incidunt natus necessitatibus odio quas sequi vero voluptatum! Aut consectetur cupiditate debitis deserunt dolor error fugiat harum hic id labore, molestiae nostrum obcaecati, pariatur quibusdam quidem soluta voluptatum. Blanditiis corporis fugit possimus quam?'],
            ['post_excerpt', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quaerat, quisquam.'],
            ['post_url', 'zagolovok_tovara'],
            ['post_cat_name', 'Категория'],
            ['post_cat_url', 'kategoriya'],
        ]);

        switch (type) {
            case 'post':
                defaultArr = defaultArrPost;
            break
            case 'page':
                defaultArr = defaultArrPage
            break;
            case 'product':
                defaultArr = defaultArrProd
            break;
            default:
                defaultArr = defaultArrPost;
        }

        return defaultArr;
    }

    function setDefaultFields() {
        let defaultArr = [];

        let postTypes = document.querySelectorAll('.switcher-select input[type="radio"]');
        postTypes && postTypes.forEach(postType => {
            if (postType.checked) {
                defaultArr = getDefaultFieldsArray(postType.value);
            }
        });

        defaultArr.forEach(function(value,key) {
            let input = document.querySelector('#'+key);
            if (input) input.value = value;
        });
    }

    function clearFields() {
        let inputs = document.querySelectorAll('#main_form input, #main_form textarea');
        inputs.forEach(input => {
            input.value = '';
        });
    }

    function getDateFormat() {
        let date = new Date();
        return date.toLocaleString("sv-SE");
    }

    function translit(word){
        var converter = {
            'а': 'a',    'б': 'b',    'в': 'v',    'г': 'g',    'д': 'd',
            'е': 'e',    'ё': 'e',    'ж': 'zh',   'з': 'z',    'и': 'i',
            'й': 'y',    'к': 'k',    'л': 'l',    'м': 'm',    'н': 'n',
            'о': 'o',    'п': 'p',    'р': 'r',    'с': 's',    'т': 't',
            'у': 'u',    'ф': 'f',    'х': 'h',    'ц': 'c',    'ч': 'ch',
            'ш': 'sh',   'щ': 'sch',  'ь': '',     'ы': 'y',    'ъ': '',
            'э': 'e',    'ю': 'yu',   'я': 'ya'
        };

        word = word.toLowerCase();

        var answer = '';
        for (var i = 0; i < word.length; ++i ) {
            if (converter[word[i]] == undefined){
                answer += word[i];
            } else {
                answer += converter[word[i]];
            }
        }

        answer = answer.replace(/[^-0-9a-z]/g, '-');
        answer = answer.replace(/[-]+/g, '-');
        answer = answer.replace(/^\-|-$/g, '');
        return answer;
    }
}
