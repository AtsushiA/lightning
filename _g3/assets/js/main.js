(()=>{var e={101:e=>{"use strict";e.exports=i,e.exports.isMobile=i,e.exports.default=i;var n=/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series[46]0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i,t=/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series[46]0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino|android|ipad|playbook|silk/i;function i(e){e||(e={});var i=e.ua;if(i||"undefined"==typeof navigator||(i=navigator.userAgent),i&&i.headers&&"string"==typeof i.headers["user-agent"]&&(i=i.headers["user-agent"]),"string"!=typeof i)return!1;var o=e.tablet?t.test(i):n.test(i);return!o&&e.tablet&&e.featureDetect&&navigator&&navigator.maxTouchPoints>1&&-1!==i.indexOf("Macintosh")&&-1!==i.indexOf("Safari")&&(o=!0),o}}},n={};function t(i){if(n[i])return n[i].exports;var o=n[i]={exports:{}};return e[i](o,o.exports,t),o.exports}!function(e){if(void 0===e.ltg){var n=function(e,n){Array.prototype.forEach.call(document.querySelectorAll(e),n)};e.ltg={},e.ltg.action=n,e.ltg.removeClass=function(e,t){n(e,(function(e){return e.classList.remove(t)}))},e.ltg.addClass=function(e,t){n(e,(function(e){return e.classList.add(t)}))},e.ltg.swap=function(e,t,i){n(e,(function(e){e.classList.remove(t),e.classList.add(i)}))}}}(window),(()=>{!function(e,n){function t(e,t){Array.prototype.forEach.call(n.querySelectorAll(e),t)}function i(e,n){t(e,(function(e){return e.classList.remove(n)}))}function o(e,n){t(e,(function(e){return e.classList.add(n)}))}!function(e,n,i){function a(e){n.getElementById("vk-mobile-nav-menu-btn").classList.remove("menu-open"),n.getElementById("vk-mobile-nav").classList.remove("vk-mobile-nav-open")}e.addEventListener("DOMContentLoaded",(function(){var e;(e=n.getElementById("vk-mobile-nav-menu-btn")).addEventListener("click",(function(){e.classList.contains("menu-open")?a():(o(".vk-mobile-nav-menu-btn","menu-open"),n.getElementById("vk-mobile-nav").classList.add("vk-mobile-nav-open"))})),t(".vk-mobile-nav li > a",(function(e){e.addEventListener("click",(function(e){e.target.getAttribute("href").indexOf(!1)&&a()}))}))}))}(e,n),function(a){function c(){e.innerWidth<=5e3?(s(),o("ul.vk-menu-acc","vk-menu-acc-active"),t("ul.vk-menu-acc ul.sub-menu",(function(e){var t=n.createElement("span");t.classList.add("acc-btn","acc-btn-open"),t.addEventListener("click",r),e.parentNode.insertBefore(t,e),e.classList.add("acc-child-close")}))):s()}function s(){i("ul.vk-menu-acc","vk-menu-acc-active"),i("ul.vk-menu-acc li","acc-parent-open"),t("ul.vk-menu-acc li .acc-btn",(function(e){return e.remove()})),i("ul.vk-menu-acc li .acc-child-close","acc-child-close"),i("ul.vk-menu-acc li .acc-child-open","acc-child-open")}function r(e){var n=e.target,t=n.parentNode,i=n.nextSibling;n.classList.contains("acc-btn-open")?(t.classList.add("acc-parent-open"),n.classList.remove("acc-btn-open"),n.classList.add("acc-btn-close"),i.classList.remove("acc-child-close"),i.classList.add("acc-child-open")):(t.classList.remove("acc-parent-open"),n.classList.remove("acc-btn-close"),n.classList.add("acc-btn-open"),i.classList.remove("acc-child-open"),i.classList.add("acc-child-close"))}!function(){var t=!1,i=n.body.offsetWidth,o=function(){var e=n.body.offsetWidth;(e<i-8||i+8<e)&&(c(),i=e)};e.addEventListener("resize",(function(){!1!==t&&clearTimeout(t),t=setTimeout(o,500)}))}(),n.addEventListener("DOMContentLoaded",c)}()}(window,document);var e=t(101);!function(n){window.addEventListener("DOMContentLoaded",(function(){var t=e.isMobile({tablet:!0});["device-mobile","device-pc"].forEach((function(e){return n.body.classList.remove(e)})),n.body.classList.add(t?"device-mobile":"device-pc")}))}(document)})()})();
//# sourceMappingURL=main.js.map