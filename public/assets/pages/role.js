$(function(){
    $('#roleForm').validate({
        rules: {
            name: "required",
            name: {
                required: true,
                minlength: 2
            },
        },
        messages: {
            name: "Please enter a rolename",
            name: {
                required: "Please enter rolename",
                minlength: "Your rolename must consist of at least 2 characters"
            },
        }
    });
})


