$(function(){
    $('#itemForm').validate({
        errorPlacement: function (error, element) {
            var elem = $(element);
            console.log(elem);
            error.insertAfter(element);
        },
        rules: {
            title: "required",
            author: "required",
            isbn: "required",
            subject: "required",
            location: "required",
            language_id: {
                required: true,
            },
        },
    });

    $("#itemEditForm").validate({
        rules: {
            title: "required",
            author: "required",
            isbn: "required",
            subject: "required",
            location: "required",
            language_id: {
                required: true,
            },
        },
    });
})


