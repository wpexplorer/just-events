/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/components/custom-fields.js":
/*!*****************************************!*\
  !*** ./src/components/custom-fields.js ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_date__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/date */ "@wordpress/date");
/* harmony import */ var _wordpress_date__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_date__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/edit-post */ "@wordpress/edit-post");
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__);







const customFields = ({
  postType,
  postMeta,
  setPostMeta
}) => {
  if ('just_event' !== postType) {
    return null;
  }
  let showTime = true;
  const setAllDayDate = (startEnd, value) => {
    let theDate = '';
    let theTime = '';
    if ('start' === startEnd) {
      let startDate = value || postMeta._just_events_start_date;
      if (startDate) {
        theDate = (0,_wordpress_date__WEBPACK_IMPORTED_MODULE_2__.format)('Y-m-d', startDate);
      }
      theTime = '00:00:00';
    } else {
      let endDate = value || postMeta._just_events_end_date;
      if (endDate) {
        theDate = (0,_wordpress_date__WEBPACK_IMPORTED_MODULE_2__.format)('Y-m-d', endDate);
      }
      theTime = '23:59:00';
    }
    if (!theDate) {
      theDate = (0,_wordpress_date__WEBPACK_IMPORTED_MODULE_2__.format)('Y-m-d');
    }
    return `${theDate}T${theTime}`;
  };
  const onAllDayChange = value => {
    setPostMeta({
      _just_events_all_day: value
    });
    if (value) {
      setPostMeta({
        _just_events_start_date: setAllDayDate('start')
      });
      setPostMeta({
        _just_events_end_date: setAllDayDate('end')
      });
    }
  };
  const onStartDateChange = value => {
    if (undefined !== postMeta._just_events_all_day && postMeta._just_events_all_day) {
      value = setAllDayDate('start', value);
    }
    setPostMeta({
      _just_events_start_date: value
    });
    if (!postMeta._just_events_end_date || postMeta._just_events_start_date === postMeta._just_events_end_date) {
      // @note we don't set the end date equal to the start but 11:59pm because then when they change the date it won't update.
      setPostMeta({
        _just_events_end_date: value
      });
    }
  };
  const onEndDateChange = value => {
    const startDate = postMeta._just_events_start_date;
    if (!startDate) {
      return;
    } else if ((0,_wordpress_date__WEBPACK_IMPORTED_MODULE_2__.format)('Y-m-d H:i:s', value) < (0,_wordpress_date__WEBPACK_IMPORTED_MODULE_2__.format)('Y-m-d H:i:s', startDate)) {
      value = startDate;
    }
    if (postMeta._just_events_all_day) {
      value = setAllDayDate('end', value);
    }
    setPostMeta({
      _just_events_end_date: value
    });
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_5__.PluginDocumentSettingPanel, {
    title: "Just Events",
    icon: "calendar",
    initialOpen: true
  }, undefined !== postMeta._just_events_all_day && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('All Day Event?', 'just-events'),
    onChange: value => onAllDayChange(value),
    checked: postMeta._just_events_all_day,
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Enable to force the start and end times from 12:00am to 11:59pm.', 'just-events')
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Start Date', 'just-events')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.Dropdown, {
    className: "just-events-fields-dropdown-start-date",
    popoverProps: {
      placement: 'left-middle'
    },
    renderToggle: ({
      isOpen,
      onToggle
    }) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.Button, {
      isLink: true,
      onClick: onToggle,
      "aria-expanded": isOpen
    }, postMeta._just_events_start_date ? (0,_wordpress_date__WEBPACK_IMPORTED_MODULE_2__.format)('M j, Y g:i a', postMeta._just_events_start_date) : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Set Date', 'just-events')),
    renderContent: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.DateTimePicker, {
      is12Hour: true,
      currentDate: postMeta._just_events_start_date,
      onChange: newDate => onStartDateChange(newDate),
      __nextRemoveHelpButton: true,
      __nextRemoveResetButton: true
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('End Date', 'just-events')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.Dropdown, {
    className: "just-events-fields-dropdown-end-date",
    popoverProps: {
      placement: 'left-middle'
    },
    renderToggle: ({
      isOpen,
      onToggle
    }) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.Button, {
      isLink: true,
      onClick: onToggle,
      "aria-expanded": isOpen
    }, postMeta._just_events_end_date ? (0,_wordpress_date__WEBPACK_IMPORTED_MODULE_2__.format)('M j, Y g:i a', postMeta._just_events_end_date) : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Set Date', 'just-events')),
    renderContent: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.DateTimePicker, {
      is12Hour: true,
      currentDate: postMeta._just_events_end_date,
      onChange: newDate => onEndDateChange(newDate),
      __nextRemoveHelpButton: true,
      __nextRemoveResetButton: true
    })
  })), undefined !== postMeta._just_events_link && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('External Link', 'just-events'),
    value: postMeta._just_events_link,
    onChange: value => setPostMeta({
      _just_events_link: value
    })
  })));
};

// Fetch the post meta values
const applyWithSelect = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.withSelect)(select => {
  return {
    postMeta: select('core/editor').getEditedPostAttribute('meta'),
    postType: select('core/editor').getCurrentPostType()
  };
});

// Update the post meta values
const applyWithDispatch = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.withDispatch)(dispatch => {
  return {
    setPostMeta(newValue) {
      dispatch('core/editor').editPost({
        meta: newValue
      });
    }
  };
});
/* harmony default export */ __webpack_exports__["default"] = ((0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__.compose)([applyWithSelect, applyWithDispatch])(customFields));

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ (function(module) {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/compose":
/*!*********************************!*\
  !*** external ["wp","compose"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["compose"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["data"];

/***/ }),

/***/ "@wordpress/date":
/*!******************************!*\
  !*** external ["wp","date"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["date"];

/***/ }),

/***/ "@wordpress/edit-post":
/*!**********************************!*\
  !*** external ["wp","editPost"] ***!
  \**********************************/
/***/ (function(module) {

module.exports = window["wp"]["editPost"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "@wordpress/plugins":
/*!*********************************!*\
  !*** external ["wp","plugins"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["plugins"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/*!***************************************!*\
  !*** ./src/register-custom-fields.js ***!
  \***************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/plugins */ "@wordpress/plugins");
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_custom_fields__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/custom-fields */ "./src/components/custom-fields.js");
/**
 * Registers a plugin for adding items to the Gutenberg Toolbar.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/slotfills/plugin-sidebar/
 */


/**
 * Internal dependencies.
 */

(0,_wordpress_plugins__WEBPACK_IMPORTED_MODULE_0__.registerPlugin)('just-events-custom-fields', {
  render: _components_custom_fields__WEBPACK_IMPORTED_MODULE_1__["default"]
});
}();
/******/ })()
;
//# sourceMappingURL=custom-fields.js.map