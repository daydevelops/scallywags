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
/******/ 	return __webpack_require__(__webpack_require__.s = 46);
/******/ })
/************************************************************************/
/******/ ({

/***/ 46:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(47);


/***/ }),

/***/ 47:
/***/ (function(module, exports) {

window.showGameModal = function (game_id) {
    // get information for the game selected
    document.querySelector('#gm-players').innerHTML = ""; // clear player list
    document.querySelector('#gm-invites').innerHTML = ""; // clear player list

    $.ajax({
        type: 'get',
        url: '/dashboard/' + game_id,
        success: function success(res) {
            console.log(res);
            if (res.status == 1) {
                window.game = res.game;
                window.players = res.players;
                $('#gm-date').html(new Date(res.game.gamedate * 1000));
                $('#gm-private-check').prop('checked', res.game.private == 1);
                res.players.forEach(function (player, ind) {
                    var li = document.createElement('li');
                    li.innerHTML = player.name;
                    document.querySelector('#gm-players').appendChild(li);
                });
                res.invites.forEach(function (invite, ind) {
                    var li = document.createElement('li');
                    li.innerHTML = invite.name;
                    document.querySelector('#gm-invites').appendChild(li);
                });
            } else {
                console.log(res.fb);
            }
        },
        error: function error(err) {
            console.log(err.responseText);
        }
    });
    $('#game-modal').modal('show');
    $('#gm-names');
};

window.toggleGamePrivate = function () {
    // console.log('toggle private');

    $.ajax({
        type: 'post',
        url: '/game/' + window.game.id + '/toggleprivate',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function success(res) {
            console.log(res);
            $('#user-game-' + window.game.id + ' .game-type').html(res.private == "1" ? "private" : "public");
        },
        error: function error(err) {
            console.log(err.responseText);
        }
    });
    // change value in dashboard table
};

window.toggleInviteInput = function () {
    $('#invite-player-row').toggleClass('hidden');
};

window.invitePlayer = function () {
    // console.log('invite: '+$('#invite-players-list').val())
    // send ajax request
    // show success message
    var player = $('#invite-players-list').val();
    $.ajax({
        type: 'post',
        url: '/game/' + window.game.id + '/invite/' + player + '',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function success(res) {
            console.log(res);
            var li = document.createElement('li');
            li.innerHTML = $('#invite-players-list option[value=' + $("#invite-players-list").val() + ']').html();
            document.querySelector('#gm-invites').appendChild(li);
        },
        error: function error(err) {
            console.log(err.responseText);
        }
    });
    window.toggleInviteInput();
};

window.leaveGame = function () {
    // console.log('leave')
    // send ajax request
    // close modal
    // remove row from dashboard table
};

/***/ })

/******/ });