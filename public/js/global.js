/**
author :Warren
http://www.wglong.com
**/
var base_url="http://122.72.0.80:8080/";
var index_list_height=96;
var return_count=5;
function translate(data_return_code,success_fn,data){
    switch(data_return_code){
        case '6000':
            myAlert('登录已过期，请重新登录')
            window.location.href='sign.html'
            break;
        case '4900':
            myAlert('服务器内部错误')
            break;
        case '5000':
            myAlert('服务器内部错误')
            break;
        case '7101':
            myAlert('用户名重复')
            break;
        case '7102':
            myAlert('确认密码不一致')
            break;
        case '7103':
            myAlert('旧密码错误')
            break;
        case '7104':
            myAlert('新密码与旧密码相同')
            break;
        case '7105':
            myAlert('昵称已被使用')
            break;
        case '7106':
            myAlert('邮箱已被使用')
            break;
        case '7107':
            myAlert('手机号已被使用')
            break;
        case '7201':
            myAlert('用户名不存在')
            break;
        case '7202':
            myAlert('用户名或密码错误')
            break;
        case '7203':
            myAlert('登录失败次数过多')
            break;
        case '7301':
            myAlert('用户被锁定')
            break;
        case '8001':
            myAlert('上传文件格式错误')
            break;
        case '8002':
            myAlert('上传文件太大')
            break;
        case '0000':
            success_fn(data)
            break;
        default:
            myAlert('请求失败')
    }
}

Date.prototype.format = function(format)
{
    var o = {
    "M+" : this.getMonth()+1, //month
    "d+" : this.getDate(),    //day
    "h+" : this.getHours(),   //hour
    "m+" : this.getMinutes(), //minute
    "s+" : this.getSeconds(), //second
    "q+" : Math.floor((this.getMonth()+3)/3),  //quarter
    "S" : this.getMilliseconds() //millisecond
    }

    if(/(y+)/.test(format)) 
    {
        format=format.replace(RegExp.$1,(this.getFullYear()+"").substr(4 - RegExp.$1.length));
    }
    
    for(var k in o)
    {
        if(new RegExp("("+ k +")").test(format))
        {
            format = format.replace(RegExp.$1,RegExp.$1.length==1 ? o[k] : ("00"+ o[k]).substr((""+ o[k]).length));
        }
    }
    return format;
}

function goTo(page,trueOrFalse) {
	showLoading();
	$.mobile.changePage(page, {reloadPage:true,transition: "slide",reverse:trueOrFalse});
}
function goBack() {
	$.mobile.back();
}
function showLoading(){
	$.mobile.loading( "show", {theme:'b',textVisible: 'true',text: '正在为您努力加载中...'});
}

function hideLoading(){
	$.mobile.loading( "hide" );
}

function showAlert(text) {
    $.mobile.loading( "show", {textonly:true,theme:'b',textVisible: 'true',text: text});
}
function myAlert(text) {
    showAlert(text);
    setTimeout(hideLoading, 2000);
}
/*function confirm(t, fn1, fn2) {
    if (t == null || t == "") t = "系统信息";
    $("#popupConfirm h1").html(t);
    $("#popupConfirm a:contains('确定')").unbind("click").click(function() {
        $("#popupConfirm").popup("close"); 
        if (fn1) { fn1() }; 
    });
    $("#popupConfirm a:contains('按错了')").unbind("click").click(function() { 
        $("#popupConfirm").popup("close"); 
        if (fn2) { fn2() }; 
    });
    $("#popupConfirm").popup("open");
}*/
function includeScript(url, callback) {
  var script = document.createElement("script");
  var doc = document.getElementsByTagName("script")[0];
  script.type = "text/javascript";
  script.src = url;
  doc.parentNode.insertBefore(script, doc);
  if (script.readyState) { //IE
      script.onreadystatechange = function () {
          if (script.readyState == "loaded" || script.readyState == "complete") {
              script.onreadystatechange = null;
              callback();
          }
      };
  } else { //标准的DOM浏览器
      script.onload = function () {
          callback();
      };
  }
  console.log("created succeed"); //提示加载成功
}

function getGeocodeUrl(lat,lng){
    return "http://maps.googleapis.com/maps/api/geocode/json?"
                + "latlng=" + (lat + "," + lng)
                + "&sensor=" + false
                + "&language=" + "en";
} 
function getUrlParam(string) {  
    var obj =  new Array();  
	    if (string.indexOf("?") != -1) {  
	        var string = string.substr(string.indexOf("?") + 1); 
	        var strs = string.split("&");  
	        for(var i = 0; i < strs.length; i ++) {  
	            var tempArr = strs[i].split("=");  
	            obj[i] = tempArr[1];
	        }  
	    }  
	    return obj;  
} 

function getgeolocation(){
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition,showError,{timeout: 5000});
    } else { 
        alert("这个浏览器不支持地理定位O(∩_∩)O~")
    }  
}
function getLocation(){
    if(confirm('是否共享位置信息?')){
        getgeolocation()
    }else{
        $.mobile.changePage('transition.html');
    }
}
function showPosition(position) {
    $.mobile.loading( "show", {theme:'b',textVisible: 'true',text: '正在定位您的位置...'});
    lat = position.coords.latitude;
    lon = position.coords.longitude;
    sessionStorage.setItem('lat',lat)
    sessionStorage.setItem('lon',lon)
    url=getGeocodeUrl(lat,lon) 
    var ajaxLocationTimeout=$.ajax({
        type: 'GET',
        url: url,
        timeout:30000,
        dataType: 'json',
        success: function(data) {
            result={'country':'','city':''}
            for(p=0;p<data['results'].length;p++){
                cityArray=data['results'][p]['address_components'];
                for(i=0;i<cityArray.length;i++){
                    var get=false;
                    array=cityArray[i]['types']
                    if(result['country']==''){
                        for(l=0;l<array.length;l++){
                            if(array[l]=='country'){
                                result['country']=cityArray[i]['short_name']
                                get=true;
                                break;
                            }
                        }
                    }
                    if(!get && result['city']==''){
                        for(l=0;l<array.length;l++){
                            if(array[l]=='locality'){
                                result['city']=cityArray[i]['long_name']
                                break;
                            }
                        }
                    }
                }
            }
            result['latitude']=lat
            result['longitude']=lon
            result['auth_token']=localStorage.getItem('auth_token')
            var ajaxGeoTimeout=$.ajax({
                type: 'GET',
                url: '/destination/getCityCode',
                timeout:30000,
                data:result,
                /*data:{'country':'GB','city':'London','longitude':-0.140261,'latitude':51.520838,'auth_token':'4283E60FB5F605B7E7E36CA0BD7E6EC3'},*/
                dataType: 'json',
                success: function(data) {
                    /*境内:0,境外:1*/
                    city_code=data['data'][0]['city_code']
                    city_name=data['data'][0]['city_name']
                    outside=data['data'][0]['outside']
                    localStorage.setItem('outside',outside)
                    window.location.href='index.html?merchant_id=0&city_code='+city_code+'&return_count=5&start_index=0&ticket_type=&longitude=0&latitude=0&orderby=0&auth_token='+result['auth_token']+'&city_name='+city_name+'&outside='+outside
                },
                complete:function(XMLHttpRequest,status){
            　　　　if(status=='timeout'){
             　　　　　 ajaxGeoTimeout.abort();
                        hideLoading()
            　　　　　  alert("请求超时");
            　　　　}
            　　}
            }) 
        },
        complete : function(XMLHttpRequest,status){
    　　　　if(status=='timeout'){
     　　　　　 ajaxLocationTimeout.abort();
                hideLoading()
    　　　　　  alert("请求超时");
    　　　　}
    　　}
    })
}
function showError(error) {
    sessionStorage.setItem('isopen',false)
    switch(error.code) {
        case error.PERMISSION_DENIED:
            alert("您拒绝开启定位服务")
            $.mobile.changePage('transition.html');
            break;
        case error.POSITION_UNAVAILABLE:
            alert("位置信息是不可用的")
            break;
        case error.TIMEOUT:
            alert("获取用户位置请求超时.")
            break;
        case error.UNKNOWN_ERROR:
            alert("未知错误.")
            break;
    }
} 
var myScroll,pullUpEl, pullUpOffset,
                generatedCount = 0;
function loaded2() {
    if(myScroll!=null){
        myScroll.destroy();
    }
    pullUpEl = document.getElementById('pullUp');   
    pullUpOffset = pullUpEl.offsetHeight;
    myScroll = new iScroll('wrapperContent1', {
        scrollbarClass: 'myScrollbar',
        useTransition: false,
        onRefresh: function () {
            if (pullUpEl.className.match('loading')) {
                pullUpEl.className = 'hide';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多...';
            }
        },
        onScrollMove: function () {
            if (this.y < (this.maxScrollY - 5) && !pullUpEl.className.match('flip')) {
                pullUpEl.className = 'flip';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = '松手开始更新...';
                this.maxScrollY = this.maxScrollY;
            } else if (this.y > (this.maxScrollY + 5) && pullUpEl.className.match('flip')) {
                pullUpEl.className = 'hide';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多...';
                this.maxScrollY = pullUpOffset;
            }
        },
        onScrollEnd: function () {
            if (pullUpEl.className.match('flip')) {
                pullUpEl.className = 'loading';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = '加载中...';                
                pullUpAction();
            }
        }
    });
    
    setTimeout(function () { document.getElementById('wrapperContent1').style.left = '0'; }, 800);
}
Array.prototype.inArray = function (elem) {
	indexOf = Array.prototype.indexOf
	if (!this) {
		return -1;
	}
	if (indexOf) {
		return indexOf.call(this, elem);
	}
	for (var i = 0, length = this.length; i < length; i++) {
		if (this[i] === elem) {
			return i;
		}
	}
	return -1;
}