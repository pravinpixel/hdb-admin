$(function(){
    $('#staffForm').validate({
        rules: {
            member_id:{
                required: true,
                minlength: 6
            },
            first_name: "required",
            last_name: "required",
            email: {
                required: true,
                email: true
            },
            designation: "required",
            group: "required",
            location: "required",
            collection: "required",
            imprint: "required",
        },
    });

    $("#staffFormEdit").validate({
        rules: {
            member_id:{
                required: true,
                minlength: 6
            },
            first_name: "required",
            last_name: "required",
            email: {
                required: true,
                email: true
            },
            designation: "required",
            group: "required",
            location: "required",
            collection: "required",
            imprint: "required",
        },
    });
});