jQuery(document).ready(function ($) {

    $('.twg-color').wpColorPicker();

    $('.twg-upload').on('click', function (e) {
        e.preventDefault();

        const input = $(this).prev('input');

        const frame = wp.media({
            title: 'Select Image',
            button: { text: 'Use this image' },
            multiple: false
        });

        frame.on('select', function () {
            const attachment = frame.state().get('selection').first().toJSON();
            input.val(attachment.url);
        });

        frame.open();
    });
});
