<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
function sendEmail() {
    let name = $("#name");
    let email = $("#email");
    let subject = $("#subject");
    let body = $("#body");

    if (isNotEmpty(name) && isNotEmpty(email) && isNotEmpty(body)) {
        $.ajax({
            url: 'sendEmail.php',
            method: 'POST',
            dataType: 'json',
            data: {
                name: name.val(),
                email: email.val(),
                subject: subject.val(),
                body: body.val()
            },
            error: function (response, status, message) {
                console.error('status', status);
                console.error('message', message);

            },
            success: function (response) {
                $('#myForm')[0].reset();
                $('.sent-notification').text('Message sent succesfully.');
            }


        });

    }
}

function isNotEmpty(caller) {

    if (caller.val() == '') {

        caller.css('border', '1px solid red');
        return false;
    } else {
        caller.css('border', '');
        return true;
    }
}
