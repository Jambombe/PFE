
if (! Cookies.get('accepts-cookies')) {
    $('.cookie.nag')
        .nag('show')
    ;
}



function acceptCookies() {
    Cookies.set('accepts-cookies', true);
    $('.cookie.nag').removeAttr('style');
}