$(document).on('mobileinit',function(){
    if (navigator.userAgent.indexOf("Android") != -1) 
   { 
     $.mobile.defaultPageTransition = 'none'; 
     $.mobile.defaultDialogTransition = 'none'; 
   }  
    $.mobile.touchOverflowEnabled =true;
    $.mobile.minScrollBack=150;
    $.mobile.transitionFallbacks.slide = "none";
    $.mobile.buttonMarkup.hoverDelay = "false";
    $.mobile.loader.prototype.options.text = "正在为您努力加载中";
	$.mobile.loader.prototype.options.textVisible = true;
	$.mobile.loader.prototype.options.theme = "b";
	$.mobile.loader.prototype.options.html = "";

    /*$.mobile.loadingMessage='正在为您努力加载中';
    $.mobile.loadingMessageTextVisible=true;
    $.mobile.pageLoadErrorMessage='加载页面出错';*/
})