(($) => {
    $(document).ready(function () {


        $('#main_form').submit(function (e) {
            e.preventDefault();
            let data = $(this).serialize();

            $.ajax({
                url: form_data.ajax_url,
                data: {
                    action: 'get_xml_file',
                    nonce: form_data.nonce,
                    form: data,
                },
                method: 'POST',
                success: function(response){
                    if (response.success && response.data.file_link) {
                        let link = document.createElement("a");
                        link.setAttribute('href',response.data.file_link);
                        link.setAttribute('download',response.data.file_name);
                        $(link).text('Скачать XML файл');
                        link.click();

                        $('.main_form__xml_content').html(link);
                    } else {
                        console.log(response.message)
                    }
                },
                error: function (jqXHR, exception) {
                    console.log('jqXHR ', jqXHR);
                    console.log('exception ', exception);
                }
            })
        });
    })
})(jQuery)