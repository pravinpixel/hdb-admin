$(function(){
    $('#itemForm').validate({
        errorPlacement: function (error, element) {
            var elem = $(element);
            console.log(elem);
            error.insertAfter(element);
        },
        rules: {
            item_id: "required",
            item_name: "required",
            category: {
                required: true,
            },
            subcategory: {
                required: true,
            },
            genre: {
                required: true,
            },
            item_type: {
                required: true,
            },
            loan_days: {
                required: true,
            },
            item_description: {
                required: true,
            },
            no_of_page: {
                required: true
            }
        },
    });

    $("#itemEditForm").validate({
        rules: {
            item_id: "required",
            item_name: "required",
            category: {
                required: true,
            },
            subcategory: {
                required: true,
            },
            genre: {
                required: true,
            },
            item_type: {
                required: true,
            },
            loan_days: {
                required: true,
            },
            item_description: {
                required: true,
            },
            no_of_page: {
                required: true
            }
        },
    });
})


