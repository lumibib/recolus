(function(o){const i=function(t){console&&console.warn&&console.warn("Recolus Script: ",t)},f=document.querySelector("script[data-recolus-site-id]");if(!f){i("No script found.");return}const d=f.getAttribute.bind(f),p=d("data-recolus-site-id"),b=d("data-recolus-host-url");if(!p){i("No site id found.");return}if(!b){i("No host url found.");return}document.doctype||i("Add DOCTYPE html for more accurate dimensions.");const E=d("data-recolus-domain")||"",I=d("data-recolus-variable")||"";o.location,o.document,o.navigator,o.history;var m=0,h=null;const s={siteId:p,hostUrl:b,customDomain:E,customVariable:I},v=function(t){const n=t.substring(-1)==="/";return t+(n===!0?"":"/")+"api/collect"},A=function(t){const n=navigator.webdriver||o.__nightmare||"callPhantom"in o||"_phantom"in o||"phantom"in o,e=/bot|crawler|crawl|spider|crawling/i.test(t);return n!=null?n:e},R=function(){return document.visibilityState==="hidden"},L=function(t){var n=/^localhost$|^127(?:\.[0-9]+){0,2}\.[0-9]+$|^(?:0*\:)*?:?0*1$/.test(location.hostname)||location.protocol==="file:";return n&&i("Run from local or file host."),n},_=function(){return([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g,t=>(t^crypto.getRandomValues(new Uint8Array(1))[0]&15>>t/4).toString(16))},k=function(){if(location.hash==="#toggle-recolus")if(localStorage.getItem("skiprecolus")==="1")localStorage.removeItem("skiprecolus","1"),alert("Recolus is now ENABLED in this browser.");else{localStorage.setItem("skiprecolus","1"),alert("Recolus is now DISABLED in this browser until "+location+" is loaded again."),i("Disabled. Recolus is now DISABLED in this browser until "+location+" is loaded again.");return}};var y=function(){return h==null&&(h=_()),h};const T=function(){const t=navigator.userAgent,n=(navigator.language||navigator.userLanguage).substr(0,2);var e;try{e=Intl.DateTimeFormat().resolvedOptions().timeZone}catch{}return{type:"page",siteId:s.siteId,rid:y(),url:location.href,host:location.host,hostname:location.hostname,protocol:location.protocol,path:location.pathname,query:location.search,fragment:location.hash,title:document.title,referrer:document.referrer,language:n,userAgent:t,timezone:e,windowWidth:o.innerWidth,windowHeight:o.innerHeight,screenWidth:o.screen.width,screenHeight:o.screen.height,customDomain:s.customDomain,customVariable:s.customVariable}},V=function(t,n){let e=new XMLHttpRequest;e.open("POST",t),e.setRequestHeader("Accept","application/json"),e.setRequestHeader("Content-Type","application/json"),e.onreadystatechange=function(){var r;e.readyState===4&&e.status!==201&&(i("Conncetion error with status "+e.status+' and message: "'+((r=JSON.parse(e.responseText))==null?void 0:r.message)+'"'),i("Conncetion error with response "+e.responseText))},e.send(JSON.stringify(n))},B=function(t,n){var e=JSON.stringify(n);navigator.sendBeacon(t,e)};if(k(),A()||R()||L()){i("Page count not send. Is a bot, in background or local.");return}V(v(s.hostUrl),T());function N(){let t=o.document;return Math.max(t.body.scrollHeight,t.documentElement.scrollHeight,t.body.offsetHeight,t.documentElement.offsetHeight,t.body.clientHeight,t.documentElement.clientHeight)}function g(){var t=o.innerHeight||(document.documentElement||document.body).clientHeight,n=N(),e=o.pageYOffset||(document.documentElement||document.body.parentNode||document.body).scrollTop,r=n-t,D=Math.floor(e/r*100);return D}o.addEventListener("load",function(){m=g(),o.addEventListener("scroll",function(){m<g()&&(m=g())},!1)});var O=function(){return Math.max(0,m,g())},c,l,u,a=this;document.hidden!=null?(c="hidden",u="visibilitychange",l="visibilityState"):document.mozHidden!=null?(c="mozHidden",u="mozvisibilitychange",l="mozVisibilityState"):document.msHidden!=null?(c="msHidden",u="msvisibilitychange",l="msVisibilityState"):document.webkitHidden!=null&&(c="webkitHidden",u="webkitvisibilitychange",l="webkitVisibilityState"),this.d=new Date,this.new_d=new Date;var H=0;o.onunload=function(){a.new_d=new Date;var t=Math.round((a.new_d-a.d)/1e3);S(t)},document.addEventListener(u,function(t){if(document[l]==="visible")a.d=new Date;else if(document[c]){a.new_d=new Date;var n=Math.round((a.new_d-a.d)/1e3);S(n)}},!1);var S=function(t){t>=1&&(H+=t,x(y(),H,O()))},x=function(t,n,e){let r={rid:t,duration:n,scroll:e};B(v(s.hostUrl)+"-update",r)}})(window);
//# sourceMappingURL=script.js.map
