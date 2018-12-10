$(document).ready(function () {
    // updating the view with notifications using ajax
    function load_unseen_notification(view = '') {
        $.ajax({
            url: "/messages",
            method: "POST",
            data: {view: view},
            dataType: "json",
            success: function (data) {
                if (data.messages.length > 0) {
                    $('.count').html(data.messages.length);
                    $('.dropdown-menu').html('');
                    $.each(data.messages, function (index, message) {
                        //console.log(message.id);
                        $('.dropdown-menu').append('<li><a href="/messages/read/' + message.id + '">' + message.title + '</a></li>');
                    });
                    /*
                    $('.dropdown-menu').html(data.notification);
                    if (data.unseen_notification > 0) {
                        $('.count').html(data.unseen_notification);
                    }
                    */
                }
            }
        });
    }

    // On appelle la fonction immédiatement
    load_unseen_notification();

    // load new notifications
    $(document).on('click', '.dropdown-toggle', function () {
        $('.count').html('');
        load_unseen_notification('yes');
    });

    setInterval(function () {
        load_unseen_notification();
    }, 5000);
});