var messageActiveUser = 0;
var messageActiveUserName = '';
$('.message-form').hide();
$('.message-users a').click(function () {
    $('.message-users li').removeClass('active');
    $(this).parent('li').addClass('active');
    messageActiveUser = $(this).attr('data-id');
    messageActiveUserName = $(this).attr('data-name');
    getMessages(messageActiveUser, 0)
    setInterval(function () {
        var messageId = $('.message-content p').last().attr('data-id');
        getMessages(messageActiveUser, messageId)
    }, 5000);
    return false;
})
$('.message-form form').on('submit', function () {
    var text = $(this).find('textarea').val();
    if (text == '') {
        return false;
    }
    $(this).trigger('reset');
    $(this).find('textarea').focus();
    $.ajax({
        url: '/message/sendMessage',
        data: {text: text, receiver: messageActiveUser},
        dataType: 'html'
    }).done(function (data) {
        var messageId = $('.message-content p').last().attr('data-id');
        getMessages(messageActiveUser, messageId);
    });
    return false;
});

function getMessages(sender, messageId) {
    $.ajax({
        url: "/message/getMessages",
        data: {sender: sender, messageId: messageId},
        dataType: 'json',
        context: document.body
    }).done(function (response) {
        var result = '';
        $.each(response.messages, function (id, message) {
            var name = '';
            if (message.sender_id == messageActiveUser) {
                name = messageActiveUserName;
            } else {
                name = 'Я';
            }
            result = result + '<p data-id="' + message.id + '"><strong>' + message.created + ' ' + name + '</strong><br>' + message.text + '</p>';
        });

        if (messageId) {
            $('.message-content').append(result);
        } else {
            $('.message-content').html(result);
        }
        var height = $("body").height();
        $("html,body").animate({"scrollTop": height}, 0);
        $('.message-form').show().find('textarea').focus();
        checkNewMessages();
    });
}

function checkNewMessages() {
    $.ajax({
        url: "/message/countNewMessages",
        dataType: 'json',
        context: document.body
    }).done(function (response) {
        $('.badge').hide();
        if (response.all) {
            $("#messagesCounter").text(response.all).show();
        }
        $('.message-users li a').each(function (index) {
            var userId = $(this).attr('data-id');
            if (response[userId]) {
                $(this).find('.badge').text(response[userId]).show();
            }
        });
    });
}
setInterval(checkNewMessages(), 10000);