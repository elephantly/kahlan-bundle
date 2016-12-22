App = {};
App.main = function(){
    emojify.setConfig({mode : 'data-uri'});
    emojify.run();

    if( 1 === $('nav').children().length )
    {
        $('nav').remove();
        $('.menu-link').remove();
        $('.push').css({
            "width": "100%",
            "margin-left":"0"
        });
        $('.push header').css({
            "width": "100%",
        });
    }

    var current = window.location.pathname.substring(1, window.location.pathname.length);
    $('nav li[data-relative="'+current+'"]').addClass('current');
}

App.main();
