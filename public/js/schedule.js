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
/******/ 	return __webpack_require__(__webpack_require__.s = 50);
/******/ })
/************************************************************************/
/******/ ({

/***/ 50:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(51);


/***/ }),

/***/ 51:
/***/ (function(module, exports) {

///////////////////// GLOBAL VARIABLES /////////////////////////
window.players = [];
window.users = [];
window.games = [];
window.events = [];
window.calendar;
window.emptyCourtHTML = "<p class='text-center'>This court is free!</p><button class='btn btn-lrg btn-primary' onclick='showNewGameModal()'>Book Now</button>";

////////////////////////////////////////////////////////////////

///////////////////// SETUP ///////////////////////////////////
window.addEventListener('load', function () {
	$.getJSON('/game/schedule', function (data) {
		console.log(data);
		window.games = data.games;
		console.log(window.games);
		window.events = data.events;
		window.users = data.users;
		initCalendar();
		showGames(window.events);
	});

	$('#filter-player').on('change', filterGames);
	$('#filter-gt').on('change', filterGames);
});

window.initCalendar = function () {
	console.log('initializing calendar');
	$('#calendar').fullCalendar({
		defaultView: 'agendaWeek',
		allDaySlot: false,
		slotEventOverlap: false,
		slotDuration: '00:40:00',
		minTime: '06:00:00',
		maxTime: '22:00:01',
		slotLabelFormat: 'H:mm',
		height: 'auto',
		timezone: 'local',
		dayClick: function dayClick(date) {
			window.dateSelected = date;
			showNewGameModal();
		},
		eventClick: function eventClick(evt) {
			showGameModal(evt);
		}
	});
	window.calendar = $('#calendar').fullCalendar('getCalendar');
};

window.showGames = function (events) {
	console.log('showing events');
	events.forEach(function (evt, key) {
		// debugger
		if (evt.title.charAt(0) == '1') {
			// 1 court is booked
			evt.className = 'one-court booking';
		} else if (evt.title.charAt(0) == '2') {
			// 2 courts are booked
			evt.className = 'two-court booking';
		}
		$('#calendar').fullCalendar('renderEvent', evt, true);
	});
	// console.log(window.events);
};
////////////////////////////////////////////////////////////////


///////////////////// NEW GAME MODAL FUNCTIONS /////////////////
window.showNewGameModal = function () {
	window.players = [];
	$('#players-list').html("");
	$('#invite-players-row').addClass('hidden');
	$('#new-game-date').html(window.dateSelected.format('ddd, MMM Do, HH:mm'));
	$('#game-modal').modal('hide');
	$('#new-game-modal').modal('show');
};
window.toggleInviteInput = function () {
	// return false;
	$('#invite-player-row').toggleClass('hidden');
};
window.togglePlayer = function (player_id) {
	player = document.querySelector('#invite-player-' + player_id);
	if (player.checked) {
		window.players.push(player_id);
		console.log(window.players);
		refreshPlayers();
	} else {
		var ind = window.players.indexOf(player_id);
		if (ind != -1) {
			window.players.splice(ind, 1);
			console.log(window.players);
			refreshPlayers();
		}
	}
};
window.refreshPlayers = function () {
	// refresh the players section in the new game modal
	document.querySelector('#players-list').innerHTML = "";
	window.players.forEach(function (player, ind) {
		console.log('adding player: ' + player);
		addPlayer(player);
	});
};
window.addPlayer = function (id) {
	var wrapper = document.querySelector('#players-list');
	var player = document.createElement('p');
	player.id = 'player-' + id;
	player.classList.add('player');
	player.innerHTML = document.querySelector('#invite-player-' + id).value;
	player.innerHTML += "<span class='rem-player-btn' onclick=removePlayer(" + id + ")><i class='fas fa-times-circle'></i></span>";
	console.log(player);
	wrapper.appendChild(player);
};
window.removePlayer = function (id) {
	document.querySelector('#invite-player-' + id).checked = false;
	togglePlayer(id);
};
window.newGame = function () {
	// var date = Math.floor(new Date($('#new-game-date').val())/1000);
	// debugger;
	// return false;
	var users = window.players;
	console.log(users);
	var isPrivate = document.querySelector('#gm-private-check').checked ? 1 : 0;
	$.ajax({
		type: 'post',
		url: '/game',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		data: {
			'gamedate': window.dateSelected.format(), // blow my god damn brains out
			'users': users,
			'private': isPrivate
		},
		success: function success(res) {
			console.log(res);
			if (res.status == 1) {
				location.reload();
			}
		},
		error: function error(err) {
			console.log(err.responseText);
		}
	});
	return false;
};
////////////////////////////////////////////////////////////////

//////////////////////// EDIT GAME FUNCTIONS ///////////////////

window.showGameModal = function (evt) {
	window.dateSelected = evt.start;
	$('#game-modal-date').html(evt.start.format('ddd, MMM Do, HH:mm'));

	// get games associated with this event
	var games = window.games; // clone
	games = games.filter(function (game) {
		return game.event_id == evt.id;
	});

	if (games.length == 0) {
		console.log('no games');
		$('#court-1-content').html(window.emptyCourtHTML);
		$('#court-2-content').html(window.emptyCourtHTML);
	} else if (games.length == 1) {
		console.log("1 game");
		populateCourtInformation(document.querySelector('#court-1-content'), games[0]);
		$('#court-2-content').html(window.emptyCourtHTML);
	} else if (games.length == 2) {
		console.log("2 game");
		populateCourtInformation(document.querySelector('#court-1-content'), games[0]);
		populateCourtInformation(document.querySelector('#court-2-content'), games[1]);
	}
	$('#game-modal').modal('show');
};

window.populateCourtInformation = function (court, game) {
	console.log('setting info for game:');
	console.log(game);
	court.innerHTML = "";
	if (game.private == 1 && !game.isPlaying) {
		// if game is private and not owned by user
		court.innerHTML = 'This booking is private';
	} else if (game.private == 0 || game.isPlaying) {
		// if game is public or owned by user
		var header = document.createElement('h4');
		var private_btn = document.createElement('span');
		private_btn.classList.add('game-type-btn');
		private_btn.innerHTML = "Private";
		var public_btn = document.createElement('span');
		public_btn.classList.add('game-type-btn');
		public_btn.innerHTML = "Public";
		if (game.private == 1) {
			private_btn.classList.add('selected');
		} else {
			public_btn.classList.add('selected');
		}
		private_btn.onclick = function () {
			toggleGamePrivate(game, private_btn, public_btn);
		};
		public_btn.onclick = function () {
			toggleGamePrivate(game, private_btn, public_btn);
		};
		header.appendChild(private_btn);
		header.appendChild(public_btn);

		// show list of players and invites
		var player_list = document.createElement('ul');
		player_list.classList.add('d-block');
		game.users_public.forEach(function (user, key) {
			// debugger
			player = document.createElement('li');
			player.innerHTML = user.name;
			player_list.appendChild(player);
		});
		game.invites_public.forEach(function (invite, key) {
			inv = document.createElement('li');
			inv.innerHTML = invite.name;
			inv.style.color = '#888';
			player_list.appendChild(inv);
		});
		if (game.isPlaying) {

			var leave_btn = document.createElement('button');
			leave_btn.classList.add('btn', 'btn-danger', 'd-block', 'm-auto');
			leave_btn.innerHTML = "Leave Game";
			leave_btn.onclick = function () {
				leaveGame(game.id);
			};

			var invite_sel = document.createElement('select');
			invite_sel.id = 'invite-to-' + game.id;
			window.users.forEach(function (user, key) {
				if (game.users_public.find(function (up) {
					return up.pivot.user_id === user.id;
				}) || game.invites_public.find(function (up) {
					return up.pivot.user_id === user.id;
				})) {
					// ignore users already playing or invited
				} else {
					var opt = document.createElement('option');
					opt.value = user.id;
					opt.innerHTML = user.name;
					invite_sel.appendChild(opt);
				}
			});
			// create list of players not already playing or invited
			var invite_btn = document.createElement('button');
			invite_btn.classList.add('btn', 'btn-secondary', 'd-inline');
			invite_btn.innerHTML = "Invite";
			invite_btn.onclick = function () {
				invitePlayer(game.id);
			};

			var btn_row = document.createElement('div');
			btn_row.appendChild(invite_sel);
			btn_row.appendChild(invite_btn);
			btn_row.appendChild(leave_btn);
		} else {
			var join_btn = document.createElement('button');
			join_btn.classList.add('btn', 'btn-primary', 'd-inline');
			join_btn.innerHTML = "Join Game";
			join_btn.onclick = function () {
				joinGame(game.id);
			};

			if (game.isInvited) {}

			var btn_row = document.createElement('div');
			btn_row.appendChild(join_btn);
		}

		court.appendChild(header);
		court.appendChild(player_list);
		court.appendChild(btn_row);
	}
};

window.leaveGame = function (game_id) {
	$.ajax({
		type: 'post',
		url: '/game/' + game_id + '/leave',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		success: function success(res) {
			console.log(res);
			if (res.status == 1) {
				location.reload();
			}
		},
		error: function error(err) {
			console.log(err.responseText);
		}
	});
};
window.joinGame = function (game_id) {
	$.ajax({
		type: 'post',
		url: '/game/' + game_id + '/join',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		success: function success(res) {
			console.log(res);
			if (res.status == 1) {
				location.reload();
			}
		},
		error: function error(err) {
			console.log(err.responseText);
		}
	});
};
window.toggleGamePrivate = function (game, private_btn, public_btn) {
	console.log('toggle private');

	$.ajax({
		type: 'post',
		url: '/game/' + game.id + '/toggleprivate',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		success: function success(res) {
			console.log(res);
			if (res.status == 1) {
				location.reload();
			}
		},
		error: function error(err) {
			console.log(err.responseText);
		}
	});
};

window.invitePlayer = function (game_id) {
	var player = $('#invite-to-' + game_id).val();
	$.ajax({
		type: 'post',
		url: '/game/' + game_id + '/invite/' + player + '',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		success: function success(res) {
			console.log(res);
			if (res.status == 1) {
				location.reload();
			}
		},
		error: function error(err) {
			console.log(err.responseText);
		}
	});
	window.toggleInviteInput();
};
////////////////////////////////////////////////////////////////

//////////////////////// MISC FUNCTIONS ///////////////////

window.filterGames = function () {
	console.log('filtering');
	// clear the getCalendar
	$('#calendar').fullCalendar('removeEvents');
	// debugger
	// get the filter data
	var player_to_find = $('#filter-player').val();
	var gt = $('#filter-gt').val();

	var event_ids = [];
	var events_to_show = [];
	if (gt == 'any' && player_to_find == '0') {
		// no filters being applied
		// debugger
		showGames(window.events);
	} else {
		// check each game
		// debugger
		window.games.forEach(function (game, key) {
			chk1 = false; // check 1: is the game type correct?
			chk2 = false; // check 2: is the player_to_find a member of the game?

			// filter by game type first
			if (gt == 'public' && game.private == 0) {
				chk1 = true;
			} else if (gt == 'private' && game.private == 1) {
				chk1 = true;
			} else if (gt == 'any') {
				chk1 = true;
			}

			// short circuit
			if (chk1 && player_to_find !== '0') {

				// filter by player
				// for private games, you cant see players unless the user is one
				// if filtering by player and private, only show the users private games if applicable
				if (game.users_public) {
					var players = game.users_public;
					players.forEach(function (player, key) {
						if (player.pivot.user_id == player_to_find) {
							chk2 = true;
						}
					});
				}
			} else {
				chk2 = true;
			}

			if (chk1 && chk2) {
				event_ids.push(game.event_id);
			}
		});
		window.events.forEach(function (event, key) {
			// debugger
			if ($.inArray(event.id, event_ids) !== -1) {
				events_to_show.push(event);
			}
		});
		// debugger
		showGames(events_to_show);
	}
};

/***/ })

/******/ });