$(function(){
    $('#userForm').validate({
        rules: {
            member_id:{
                required: true,
                minlength: 3
            },
            first_name: "required",
            last_name: "required",
            username: {
                required: true,
                minlength: 2
            },
            password: {
                required: true,
                minlength: 5
            },
            password_confirmation: {
                required: true,
                minlength: 5,
                equalTo: "#password"
            },
            email: {
                required: true,
                email: true
            },
            topic: {
                required: "#newsletter:checked",
                minlength: 2
            },
            role: "required"
        },
        messages: {
            member_id: {
                required: "Please enter your member id",
                minlength: "Your member id must be at least 5 characters long"
            },
            first_name: "Please enter your firstname",
            last_name: "Please enter your lastname",
            username: {
                required: "Please enter a username",
                minlength: "Your username must consist of at least 2 characters"
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long"
            },
            password_confirmation: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long",
                equalTo: "Please enter the same password as above"
            },
            email: "Please enter a valid email address",
            role: "Please select role"
        }
    });

    $("#userFormEdit").validate({
        rules: {
            first_name: "required",
            last_name: "required",
            username: {
                required: true,
                minlength: 2
            },
         
            email: {
                required: true,
                email: true
            },
            topic: {
                required: "#newsletter:checked",
                minlength: 2
            },
            role: "required"
        },
        messages: {
            first_name: "Please enter your firstname",
            last_name: "Please enter your lastname",
            username: {
                required: "Please enter a username",
                minlength: "Your username must consist of at least 2 characters"
            },
            email: "Please enter a valid email address",
            role: "Please select role"
        }
    });

    $("#userProfileFormEdit").validate({
        rules: {
            old_password: {
                required: true,
                minlength: 5
            },
            new_password: {
                required: true,
                minlength: 5
            },
            password_confirmation: {
                required: true,
                minlength: 5,
                equalTo: "#new_password"
            },
        },
        messages: {
            old_password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long"
            },
            new_password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long"
            },
            password_confirmation: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long",
                equalTo: "Please enter the same password as above"
            }
        }
    });
    
})