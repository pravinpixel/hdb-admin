$(function(){
    $('#itemForm').validate({
        errorPlacement: function (error, element) {
            var elem = $(element);
            console.log(elem);
            error.insertAfter(element);
        },
        rules: {
            item_ref: "required",
            title: "required",
            author: "required",
            isbn: "required",
            subject: "required",
            location: "required",
            collection: "required",
            imprint: "required",
            language_id: {
                required: true,
            },
        },
    });

    $("#itemEditForm").validate({
        rules: {
            item_ref: "required",
            title: "required",
            author: "required",
            isbn: "required",
            subject: "required",
            location: "required",
            collection: "required",
            imprint: "required",
            language_id: {
                required: true,
            },
        },
    });
})


