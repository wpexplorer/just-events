!function(){"use strict";var e=window.wp.plugins,t=window.wp.element,n=window.wp.i18n,s=window.wp.date,a=window.wp.compose,_=window.wp.data,o=window.wp.editPost,r=window.wp.components;const l=(0,_.withSelect)((e=>({postMeta:e("core/editor").getEditedPostAttribute("meta"),postType:e("core/editor").getCurrentPostType()}))),d=(0,_.withDispatch)((e=>({setPostMeta(t){e("core/editor").editPost({meta:t})}})));var u=(0,a.compose)([l,d])((({postType:e,postMeta:a,setPostMeta:_})=>{if("just_event"!==e)return null;const l=(e,t)=>{let n="",_="";if("start"===e){let e=t||a._just_events_start_date;e&&(n=(0,s.format)("Y-m-d",e)),_="00:00:00"}else{let e=t||a._just_events_end_date;e&&(n=(0,s.format)("Y-m-d",e)),_="23:59:00"}return n||(n=(0,s.format)("Y-m-d")),`${n}T${_}`};return(0,t.createElement)(o.PluginDocumentSettingPanel,{title:"Just Events",icon:"calendar",initialOpen:!0},void 0!==a._just_events_all_day&&(0,t.createElement)(r.PanelRow,null,(0,t.createElement)(r.CheckboxControl,{label:(0,n.__)("All Day Event?","just-events"),onChange:e=>(e=>{_({_just_events_all_day:e}),e&&(_({_just_events_start_date:l("start")}),_({_just_events_end_date:l("end")}))})(e),checked:a._just_events_all_day,help:(0,n.__)("Enable to force the start and end times from 12:00am to 11:59pm.","just-events")})),(0,t.createElement)(r.PanelRow,null,(0,t.createElement)("span",null,(0,n.__)("Start Date","just-events")),(0,t.createElement)(r.Dropdown,{className:"just-events-fields-dropdown-start-date",popoverProps:{placement:"left-middle"},renderToggle:({isOpen:e,onToggle:_})=>(0,t.createElement)(r.Button,{isLink:!0,onClick:_,"aria-expanded":e},a._just_events_start_date?(0,s.format)("M j, Y g:i a",a._just_events_start_date):(0,n.__)("Set Date","just-events")),renderContent:()=>(0,t.createElement)(r.DateTimePicker,{is12Hour:!0,currentDate:a._just_events_start_date,onChange:e=>{return t=e,void 0!==a._just_events_all_day&&a._just_events_all_day&&(t=l("start",t)),_({_just_events_start_date:t}),void(a._just_events_end_date&&a._just_events_start_date!==a._just_events_end_date||_({_just_events_end_date:t}));var t},__nextRemoveHelpButton:!0,__nextRemoveResetButton:!0})})),(0,t.createElement)(r.PanelRow,null,(0,t.createElement)("span",null,(0,n.__)("End Date","just-events")),(0,t.createElement)(r.Dropdown,{className:"just-events-fields-dropdown-end-date",popoverProps:{placement:"left-middle"},renderToggle:({isOpen:e,onToggle:_})=>(0,t.createElement)(r.Button,{isLink:!0,onClick:_,"aria-expanded":e},a._just_events_end_date?(0,s.format)("M j, Y g:i a",a._just_events_end_date):(0,n.__)("Set Date","just-events")),renderContent:()=>(0,t.createElement)(r.DateTimePicker,{is12Hour:!0,currentDate:a._just_events_end_date,onChange:e=>(e=>{const t=a._just_events_start_date;t&&((0,s.format)("Y-m-d H:i:s",e)<(0,s.format)("Y-m-d H:i:s",t)&&(e=t),a._just_events_all_day&&(e=l("end",e)),_({_just_events_end_date:e}))})(e),__nextRemoveHelpButton:!0,__nextRemoveResetButton:!0})})),void 0!==a._just_events_link&&(0,t.createElement)(r.PanelRow,null,(0,t.createElement)(r.TextControl,{label:(0,n.__)("External Link","just-events"),value:a._just_events_link,onChange:e=>_({_just_events_link:e})})))}));(0,e.registerPlugin)("just-events-custom-fields",{render:u})}();