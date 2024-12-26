$(function(){
    $('#typeForm').validate({
        rules: {
            language: "required",
            language: {
                required: true,
                minlength: 2
            },
        },
        messages: {
            language: "Please enter a Language",
            language: {
                required: "Please enter a Language",
                minlength: "Your type must consist of at least 2 characters"
            },
        }
    });
})


