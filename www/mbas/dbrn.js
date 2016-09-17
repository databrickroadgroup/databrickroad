(function(){

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
    callback('test1', 'test2');
  }



})();
