!function(){"use strict";var e,t={573:function(e,t,n){var r=window.wp.element,o=window.wp.blocks,u=(window.wp.i18n,window.wp.blockEditor),i=window.wp.serverSideRender,l=n.n(i),s=JSON.parse('{"u2":"just-events/event-status","EI":["postId"]}');(0,o.registerBlockType)(s.u2,{icon:{src:(0,r.createElement)("svg",{xmlns:"http://www.w3.org/2000/svg",height:"24",viewBox:"0 -960 960 960",width:"24",fill:"currentColor"},(0,r.createElement)("path",{d:"M200-640h560v-80H200v80Zm0 0v-80 80Zm0 560q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v227q-19-9-39-15t-41-9v-43H200v400h252q7 22 16.5 42T491-80H200Zm520 40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40Zm67-105 28-28-75-75v-112h-40v128l87 87Z"}))},edit:function({context:e,attributes:t,setAttributes:n}){const o=(0,u.useBlockProps)(),{textAlign:i}=t;let v={};return s.EI.forEach((t=>{var n;v[t]=null!==(n=e[t])&&void 0!==n?n:null})),(0,r.createElement)(r.Fragment,null,(0,r.createElement)("div",{...o},(0,r.createElement)(u.BlockControls,null,(0,r.createElement)(u.AlignmentToolbar,{value:i,onChange:e=>n({textAlign:e})})),(0,r.createElement)(l(),{block:"just-events/event-status",attributes:t,urlQueryArgs:v})))}})}},n={};function r(e){var o=n[e];if(void 0!==o)return o.exports;var u=n[e]={exports:{}};return t[e](u,u.exports,r),u.exports}r.m=t,e=[],r.O=function(t,n,o,u){if(!n){var i=1/0;for(c=0;c<e.length;c++){n=e[c][0],o=e[c][1],u=e[c][2];for(var l=!0,s=0;s<n.length;s++)(!1&u||i>=u)&&Object.keys(r.O).every((function(e){return r.O[e](n[s])}))?n.splice(s--,1):(l=!1,u<i&&(i=u));if(l){e.splice(c--,1);var v=o();void 0!==v&&(t=v)}}return t}u=u||0;for(var c=e.length;c>0&&e[c-1][2]>u;c--)e[c]=e[c-1];e[c]=[n,o,u]},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,{a:t}),t},r.d=function(e,t){for(var n in t)r.o(t,n)&&!r.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},function(){var e={885:0,995:0};r.O.j=function(t){return 0===e[t]};var t=function(t,n){var o,u,i=n[0],l=n[1],s=n[2],v=0;if(i.some((function(t){return 0!==e[t]}))){for(o in l)r.o(l,o)&&(r.m[o]=l[o]);if(s)var c=s(r)}for(t&&t(n);v<i.length;v++)u=i[v],r.o(e,u)&&e[u]&&e[u][0](),e[u]=0;return r.O(c)},n=self.webpackChunkjust_events=self.webpackChunkjust_events||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))}();var o=r.O(void 0,[995],(function(){return r(573)}));o=r.O(o)}();