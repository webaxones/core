/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./js/checkbox.js":
/*!************************!*\
  !*** ./js/checkbox.js ***!
  \************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Checkbox": function() { return /* binding */ Checkbox; }
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


const Checkbox = _ref => {
  let {
    fieldValue,
    field,
    onChange
  } = _ref;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.CheckboxControl, {
    key: field.id,
    label: field.label,
    help: field.hasOwnProperty('help') ? field.help : '',
    checked: fieldValue || false,
    onChange: value => {
      onChange(value, field.id);
    }
  });
};

/***/ }),

/***/ "./js/media.js":
/*!*********************!*\
  !*** ./js/media.js ***!
  \*********************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Media": function() { return /* binding */ Media; }
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_media_utils__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/media-utils */ "@wordpress/media-utils");
/* harmony import */ var _wordpress_media_utils__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_media_utils__WEBPACK_IMPORTED_MODULE_3__);





const ImagePreview = file => {
  if (file && typeof file === 'object' && file.url !== '') {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
      className: "wax-components-field__img",
      src: file.url,
      alt: "Preview"
    });
  }

  return '';
};

const Media = _ref => {
  let {
    fieldValue,
    field,
    onChange
  } = _ref;
  const urlParams = new URLSearchParams(window.location.search);
  const file = urlParams.get('image');

  if (file && {} === fieldValue) {
    onChange(file, field.id);
  }

  const onRemoveImage = () => {
    onChange({
      id: 0,
      url: ''
    }, field.id);
    ImagePreview({
      id: 0,
      url: ''
    });
  };

  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "upload"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    className: "wax-components-field__label"
  }, field.label), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.FormFileUpload, {
    accept: "image/*",
    icon: "format-image",
    onChange: file => {
      _wordpress_media_utils__WEBPACK_IMPORTED_MODULE_3__.uploadMedia({
        filesList: file.target.files,
        onFileChange: _ref2 => {
          let [fileObj] = _ref2;

          if ('undefined' !== typeof fileObj.id) {
            onChange({
              id: fileObj.id,
              url: fileObj.url
            }, field.id);
          }
        },
        onError: console.error
      });
    }
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Select an Image or Drop it here', 'webaxones-core')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.DropZone, {
    onFilesDrop: files => {
      _wordpress_media_utils__WEBPACK_IMPORTED_MODULE_3__.uploadMedia({
        filesList: files[0],
        onFileChange: _ref3 => {
          let [fileObj] = _ref3;

          if ('undefined' !== typeof fileObj.id) {
            onChange({
              id: fileObj.id,
              url: fileObj.url
            }, field.id);
          }
        },
        onError: console.error
      });
    }
  }), ImagePreview(fieldValue), fieldValue && typeof fieldValue === 'object' && fieldValue.url !== '' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
    onClick: onRemoveImage,
    isLink: true,
    isDestructive: true
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Remove image', 'webaxones-core'))));
};

/***/ }),

/***/ "./js/notices.js":
/*!***********************!*\
  !*** ./js/notices.js ***!
  \***********************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Notices": function() { return /* binding */ Notices; }
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_notices__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/notices */ "@wordpress/notices");
/* harmony import */ var _wordpress_notices__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_notices__WEBPACK_IMPORTED_MODULE_3__);




const Notices = () => {
  const notices = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.useSelect)(select => select(_wordpress_notices__WEBPACK_IMPORTED_MODULE_3__.store).getNotices().filter(notice => notice.type === 'snackbar'), []);
  const {
    removeNotice
  } = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.useDispatch)(_wordpress_notices__WEBPACK_IMPORTED_MODULE_3__.store);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.SnackbarList, {
    className: "edit-site-notices",
    notices: notices,
    onRemove: removeNotice
  });
};

/***/ }),

/***/ "./js/text.js":
/*!********************!*\
  !*** ./js/text.js ***!
  \********************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Text": function() { return /* binding */ Text; }
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


const Text = _ref => {
  let {
    fieldValue,
    field,
    onChange
  } = _ref;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.TextControl, {
    key: field.id,
    help: field.hasOwnProperty('help') ? field.help : '',
    label: field.label,
    type: field.type,
    value: fieldValue || '',
    onChange: value => {
      onChange(value, field.id);
    }
  });
};

/***/ }),

/***/ "./js/textArea.js":
/*!************************!*\
  !*** ./js/textArea.js ***!
  \************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "TextArea": function() { return /* binding */ TextArea; }
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


const TextArea = _ref => {
  let {
    fieldValue,
    field,
    onChange
  } = _ref;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.TextareaControl, {
    key: field.id,
    help: field.hasOwnProperty('help') ? field.help : '',
    label: field.label,
    type: 'number',
    value: fieldValue || '',
    onChange: value => {
      onChange(value, field.id);
    }
  });
};

/***/ }),

/***/ "./js/toggle.js":
/*!**********************!*\
  !*** ./js/toggle.js ***!
  \**********************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Toggle": function() { return /* binding */ Toggle; }
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


const Toggle = _ref => {
  let {
    fieldValue,
    field,
    onChange
  } = _ref;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.ToggleControl, {
    key: field.id,
    label: field.label,
    help: field.hasOwnProperty('help') ? field.help : '',
    checked: fieldValue || false,
    onChange: value => {
      onChange(value, field.id);
    }
  });
};

/***/ }),

/***/ "./css/admin.scss":
/*!************************!*\
  !*** ./css/admin.scss ***!
  \************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "@wordpress/api":
/*!*****************************!*\
  !*** external ["wp","api"] ***!
  \*****************************/
/***/ (function(module) {

module.exports = window["wp"]["api"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ (function(module) {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["data"];

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

/***/ "@wordpress/media-utils":
/*!************************************!*\
  !*** external ["wp","mediaUtils"] ***!
  \************************************/
/***/ (function(module) {

module.exports = window["wp"]["mediaUtils"];

/***/ }),

/***/ "@wordpress/notices":
/*!*********************************!*\
  !*** external ["wp","notices"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["notices"];

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
/*!*********************!*\
  !*** ./js/index.js ***!
  \*********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_api__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/api */ "@wordpress/api");
/* harmony import */ var _wordpress_api__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _css_admin_scss__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../css/admin.scss */ "./css/admin.scss");
/* harmony import */ var _notices_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./notices.js */ "./js/notices.js");
/* harmony import */ var _text_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./text.js */ "./js/text.js");
/* harmony import */ var _textArea_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./textArea.js */ "./js/textArea.js");
/* harmony import */ var _checkbox_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./checkbox.js */ "./js/checkbox.js");
/* harmony import */ var _toggle_js__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./toggle.js */ "./js/toggle.js");
/* harmony import */ var _media_js__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./media.js */ "./js/media.js");












 // Filter declarations dedicated to the current page

const objUrlParams = new URLSearchParams(window.location.search);
const pageSlug = objUrlParams.get('page');
let currentPageGroups = webaxonesApps.filter(group => group[0].page === pageSlug);

const App = () => {
  const [fields, setFields] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)([]);
  const [tabs, setTabs] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)([]);
  const [tabSelected, setTabSelected] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(currentPageGroups[0][0].group);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const getData = async () => {
      await _wordpress_api__WEBPACK_IMPORTED_MODULE_2___default().loadPromise.then(() => {
        const settings = new (_wordpress_api__WEBPACK_IMPORTED_MODULE_2___default().models.Settings)();
        settings.fetch().then(response => {
          const data = [];
          currentPageGroups.forEach(group => {
            group.forEach(field => {
              data.push({
                id: field.slug,
                label: field.label,
                help: field.help,
                value: response[field.slug],
                tab: field.group,
                type: field.type
              });
            });
          });
          setFields(data);
        });
      });
    };

    getData();
  }, []);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const getTabs = async () => {
      await _wordpress_api__WEBPACK_IMPORTED_MODULE_2___default().loadPromise.then(() => {
        const settings = new (_wordpress_api__WEBPACK_IMPORTED_MODULE_2___default().models.Settings)();
        settings.fetch().then(() => {
          const data = [];
          currentPageGroups.forEach(group => {
            data.push({
              name: group[0].group,
              title: group[0].group_name,
              className: `${group[0].group}__tab`
            });
          });
          setTabs(data);
        });
      });
    };

    getTabs();
  }, []);

  const onChangeField = (value, id) => {
    setFields(prevState => {
      return prevState.map(item => {
        if (item.id !== id) {
          return item;
        }

        return { ...item,
          value: value
        };
      });
    });
  };

  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TabPanel, {
    tabs: tabs,
    onSelect: tab => setTabSelected(tab)
  }, tab => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, tab.children)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      paddingTop: 10
    }
  }, fields.map((field, key) => {
    if (field.tab !== tabSelected) {
      return null;
    }

    if ('text' === field.type || 'number' === field.type || 'datetime-local' === field.type || 'email' === field.type) {
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        key: key,
        style: {
          marginTop: 15
        }
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_text_js__WEBPACK_IMPORTED_MODULE_7__.Text, {
        fieldValue: field.value,
        field: field,
        onChange: onChangeField
      }));
    }

    if ('textarea' === field.type) {
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        key: key,
        style: {
          marginTop: 15
        }
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_textArea_js__WEBPACK_IMPORTED_MODULE_8__.TextArea, {
        fieldValue: field.value,
        field: field,
        onChange: onChangeField
      }));
    }

    if ('checkbox' === field.type) {
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        key: key,
        style: {
          marginTop: 15
        }
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_checkbox_js__WEBPACK_IMPORTED_MODULE_9__.Checkbox, {
        fieldValue: field.value,
        field: field,
        onChange: onChangeField
      }));
    }

    if ('toggle' === field.type) {
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        key: key,
        style: {
          marginTop: 15
        }
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_toggle_js__WEBPACK_IMPORTED_MODULE_10__.Toggle, {
        fieldValue: field.value,
        field: field,
        onChange: onChangeField
      }));
    }

    if ('media' === field.type) {
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        key: key,
        style: {
          marginTop: 15
        }
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_media_js__WEBPACK_IMPORTED_MODULE_11__.Media, {
        fieldValue: field.value,
        field: field,
        onChange: onChangeField
      }));
    }
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      marginTop: 20
    }
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Button, {
    isPrimary: true,
    onClick: () => {
      const values = {};
      fields.forEach(field => {
        values[field.id] = field.value;
      });
      const settings = new (_wordpress_api__WEBPACK_IMPORTED_MODULE_2___default().models.Settings)(values);
      settings.save();
      (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.dispatch)('core/notices').createNotice('success', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Settings Saved', 'webaxones-core'), {
        type: 'snackbar',
        isDismissible: true
      });
    }
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Save', 'webaxones-core'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "wax-custom-settings__notices"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_notices_js__WEBPACK_IMPORTED_MODULE_6__.Notices, null)));
};

document.addEventListener('DOMContentLoaded', () => {
  const htmlOutput = document.getElementById('wax-company-settings__content');

  if (htmlOutput) {
    (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.render)((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(App, null), htmlOutput);
  }
});
}();
/******/ })()
;
//# sourceMappingURL=index.js.map