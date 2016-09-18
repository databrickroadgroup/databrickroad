(function(){

  const cookieConst = 'dbru';
  var pageDomain = document.domain;
  var pageLocation = window.location.pathname;
  var referrer = document.referrer;
  var page_url = pageDomain + pageLocation;
  var firstQuarterMet, halfwayMet, threeQuarterMet, bottomMet = false;

  document.addEventListener('DOMContentLoaded', function () {
      console.log('Aloha');
      console.log(pageDomain);
      processEvent(callback);
  });

  function processEvent(callback, eventType, eventDetail) {
    console.log('processing...');
    var cookie = 'blah134kd';

    if (!doesCookieExist(cookieConst)) {
      setCookie(cookieConst, cookie, 1825); // 5 yrs
    }

    var cookiemookie = getCookie(cookieConst);
    callback(cookiemookie, 'test2');
  }

  function setCookie(name, value, days) {
    var date, expires;

    if (days) {
      date = new Date();
      date.setTime(date.getTime() + (days*24*60*60*1000));
      expires = "expires=" + date.toGMTString();
    }

    document.cookie = name + '=' + value + ';' + expires + ';' + 'path=/';
  }

  function getCookie(name) {
    var cookie_array = document.cookie.match( '(^|;) ?' + name + '=([^;]*)(;|$)' );

    if (cookie_array) {
      var value = cookie_array[2];
    }

    return value;
  }

  function doesCookieExist(name, value) {
    if (getCookie(name)) {
      return true;
    } else {
      return false;
    }
  }

})();
