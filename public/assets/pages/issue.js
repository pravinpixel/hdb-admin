$(function(){
    $('#checkoutForm').validate({
        rules: {
            name: {
                required: true,
            },
            email: {
                required: true,
            },
            mobile: {
                required: true,
            },
            address: {
                required: true,
            },
        },
    });
})


