$(document).ready(function () {
    var button = $('.click-me');
    button.on('click', handleClick);
});

function sendRequest() {
    $.ajax( 'http://localhost/basic/web/site/handler',{
        data: {
            id: '123'
        },
        success: function (data) {
            console.log('1 ' + data);
        },
        error: function (data) {
        }
    });
}

function handleClick() {

    // var response = sendRequest();

    $.ajax( 'http://localhost/basic/web/site/handler',{
        data: {
            id: '123'
        },
        success: function (data) {
            console.log('1 ' + data);
        },
        error: function (data) {
        }
    }).then(function (data) {
        console.log('2 ' + data);
    });
}