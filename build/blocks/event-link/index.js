!function(){var e,t={324:function(e,t,n){"use strict";var r=window.wp.element,o=window.wp.blocks,l=n(184),a=n.n(l),i=window.wp.i18n,u=window.wp.blockEditor,s=window.wp.components,c=window.wp.serverSideRender,v=n.n(c),p=JSON.parse('{"u2":"just-events/event-link","EI":["postId"]}');(0,o.registerBlockType)(p.u2,{icon:{src:(0,r.createElement)("svg",{xmlns:"http://www.w3.org/2000/svg",height:"24",viewBox:"0 -960 960 960",width:"24",fill:"currentColor"},(0,r.createElement)("path",{d:"M440-280H280q-83 0-141.5-58.5T80-480q0-83 58.5-141.5T280-680h160v80H280q-50 0-85 35t-35 85q0 50 35 85t85 35h160v80ZM320-440v-80h320v80H320Zm200 160v-80h160q50 0 85-35t35-85q0-50-35-85t-85-35H520v-80h160q83 0 141.5 58.5T880-480q0 83-58.5 141.5T680-280H520Z"}))},edit:function({context:e,attributes:t,setAttributes:n}){const{text:o,design:l,targetBlank:c,textAlign:f}=t,g=(0,u.useBlockProps)({className:a()({[`has-text-align-${f}`]:f})});let d={};return p.EI.forEach((t=>{var n;d[t]=null!==(n=e[t])&&void 0!==n?n:null})),(0,r.createElement)(r.Fragment,null,(0,r.createElement)(u.InspectorControls,null,(0,r.createElement)(s.PanelBody,null,(0,r.createElement)(s.__experimentalToggleGroupControl,{label:(0,i.__)("Design","just-events"),value:l,onChange:e=>{n({design:e})},isBlock:!0},(0,r.createElement)(s.__experimentalToggleGroupControlOption,{value:"none",label:(0,i.__)("Default","just-events")}),(0,r.createElement)(s.__experimentalToggleGroupControlOption,{value:"button",label:(0,i.__)("Button","just-events")})),(0,r.createElement)(s.PanelRow,null,(0,r.createElement)("label",{htmlFor:"just-events-link-block-target-blank"},(0,i.__)("Open in New Tab","just-events")),(0,r.createElement)(s.FormToggle,{id:"just-events-link-block-target-blank",checked:c,onChange:()=>n({targetBlank:!c})})),(0,r.createElement)(s.PanelRow,null,(0,r.createElement)(s.TextControl,{label:(0,i.__)("Custom Text","just-events"),onChange:e=>n({text:e}),value:o})))),(0,r.createElement)("div",{...g},(0,r.createElement)(u.BlockControls,null,(0,r.createElement)(u.AlignmentToolbar,{value:f,onChange:e=>n({textAlign:e})})),(0,r.createElement)(v(),{block:"just-events/event-link",attributes:t,urlQueryArgs:d})))}})},184:function(e,t){var n;!function(){"use strict";var r={}.hasOwnProperty;function o(){for(var e=[],t=0;t<arguments.length;t++){var n=arguments[t];if(n){var l=typeof n;if("string"===l||"number"===l)e.push(n);else if(Array.isArray(n)){if(n.length){var a=o.apply(null,n);a&&e.push(a)}}else if("object"===l){if(n.toString!==Object.prototype.toString&&!n.toString.toString().includes("[native code]")){e.push(n.toString());continue}for(var i in n)r.call(n,i)&&n[i]&&e.push(i)}}}return e.join(" ")}e.exports?(o.default=o,e.exports=o):void 0===(n=function(){return o}.apply(t,[]))||(e.exports=n)}()}},n={};function r(e){var o=n[e];if(void 0!==o)return o.exports;var l=n[e]={exports:{}};return t[e](l,l.exports,r),l.exports}r.m=t,e=[],r.O=function(t,n,o,l){if(!n){var a=1/0;for(c=0;c<e.length;c++){n=e[c][0],o=e[c][1],l=e[c][2];for(var i=!0,u=0;u<n.length;u++)(!1&l||a>=l)&&Object.keys(r.O).every((function(e){return r.O[e](n[u])}))?n.splice(u--,1):(i=!1,l<a&&(a=l));if(i){e.splice(c--,1);var s=o();void 0!==s&&(t=s)}}return t}l=l||0;for(var c=e.length;c>0&&e[c-1][2]>l;c--)e[c]=e[c-1];e[c]=[n,o,l]},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,{a:t}),t},r.d=function(e,t){for(var n in t)r.o(t,n)&&!r.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},function(){var e={760:0,776:0};r.O.j=function(t){return 0===e[t]};var t=function(t,n){var o,l,a=n[0],i=n[1],u=n[2],s=0;if(a.some((function(t){return 0!==e[t]}))){for(o in i)r.o(i,o)&&(r.m[o]=i[o]);if(u)var c=u(r)}for(t&&t(n);s<a.length;s++)l=a[s],r.o(e,l)&&e[l]&&e[l][0](),e[l]=0;return r.O(c)},n=self.webpackChunkjust_events=self.webpackChunkjust_events||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))}();var o=r.O(void 0,[776],(function(){return r(324)}));o=r.O(o)}();