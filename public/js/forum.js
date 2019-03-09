/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 208);
/******/ })
/************************************************************************/
/******/ ({

/***/ 208:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(209);


/***/ }),

/***/ 209:
/***/ (function(module, exports) {

window.toggleFavourite = function (type, id) {
	// if button has class of "favourited"
	if ($('#' + type + '-' + id).hasClass('favourited')) {
		// send post to unfavourite
		$.ajax({
			type: 'post',
			url: '/unfavourite/' + type + '/' + id,
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			success: function success(res) {
				console.log(res);
				// if (res.status==1) {
				$('#' + type + '-' + id).removeClass('favourited');
				// }
			},
			error: function error(err) {
				console.log(err.responseText);
			}
		});
	} else {
		// send post to favourite
		$.ajax({
			type: 'post',
			url: '/favourite/' + type + '/' + id,
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			success: function success(res) {
				console.log(res);
				// if (res.status==1) {
				$('#' + type + '-' + id).addClass('favourited');
				// }
			},
			error: function error(err) {
				console.log(err.responseText);
			}
		});
	}
};

/***/ })

/******/ });