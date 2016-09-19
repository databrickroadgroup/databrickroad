(function(){

  const USRCOOKIE = 'dbru';
  const PGLOADEVT = 'pageload';
  const PGSCROLLEVT = 'pagescroll';
  const USRCTRL = 'user';
  const API = 'http://databrickroad.local/api';
  const APIPOST = 'POST';
  const APIGET = 'GET';
  const APIRESPONSETYPE = 'json';

  var pageDomain = document.domain;
  var pageLocation = window.location.pathname;
  var referrer = document.referrer;
  var page_url = pageDomain + pageLocation;
  var firstQuarterMet, halfwayMet, threeQuarterMet, bottomMet = false;

  document.addEventListener('DOMContentLoaded', function () {
      processEvent(callback, PGLOADEVT);
  });

  window.addEventListener('scroll', function (e) {

    var scrollAmount = window.innerHeight + window.scrollY;
    var documentHeight = $(document).height();
    var scrollPercent = (scrollAmount / documentHeight) * 100;

    if ((!firstQuarterMet) && (scrollPercent > 25)) {
      firstQuarterMet = true;
      console.log('1A');
      processEvent(callback, PGSCROLLEVT, '1/4');
    }
    if ((!halfwayMet) && (scrollPercent > 50)) {
  		halfwayMet = true;
  		console.log('2A');
  		processEvent(callback, PGSCROLLEVT, '2/4');
  	}

  	if ((!threeQuarterMet) && (scrollPercent > 75)) {
  		threeQuarterMet = true;
  		console.log('3A');
  		processEvent(callback, PGSCROLLEVT, '3/4');
  	}

  	if ((!bottomMet) && (scrollPercent > 90)) {
  		bottomMet = true;
  		console.log('4A');
  		processEvent(callback, PGSCROLLEVT, '4/4');
  	}

  });

  function processEvent(callback, eventType, eventDetail) {

    var proxy = API + '/' + eventType + '/' + USRCTRL;
    var created = getCurrentDateTime();
    var user_guid = getCookie(USRCOOKIE);

    var postData;
    if (eventType == PGLOADEVT) {
      postData = { "created":created, "user_guid":user_guid, "domain":pageDomain, "page_name":pageLocation, "page_url":page_url, "referrer":referrer};
    } else if (eventType == PGSCROLLEVT) {
      postData = { "created":created, "user_guid":user_guid, "domain":pageDomain, "page_name":pageLocation, "page_url":page_url, "referrer":referrer, "page_position_code":eventDetail};
    }

    postEvent(proxy, APIPOST, postData, function(data) {
      if (!doesCookieExist(USRCOOKIE)) {
        console.log('setting cookie');
        setCookie(USRCOOKIE, data.databrickuser, 1825);
      }

      callback(data.databrickuser, data.result);
    });
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
    var value = '';

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

  function getCurrentDateTime() {
    var date = new Date();
  	return date.getFullYear() + "-" + date.getMonth() + "-" + date.getDate() + " " +  date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
  }

  // posting to the API
  var postEvent = function(url, type, data, responseHandler, errorHandler) {
    var xhr = new XMLHttpRequest();
    xhr.open(type, url, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify(data));
    xhr.onreadystatechange = function(){
      if (xhr.readyState == 4 && xhr.status == 200) {
        var response = JSON.parse(xhr.responseText);
        responseHandler(response);
      }
    };
  };

})();
