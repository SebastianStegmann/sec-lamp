// function _(q, from=document) { return from.querySelector(q) }
// function __(q, from=document) { return from.querySelectorAll(q)}

const block_buttons = document.querySelectorAll('.user_block_button');

// TODO? Should i add admin valdiation, i think all these pages are only for admins anyway

block_buttons.forEach((block_button) => {
	block_button.addEventListener('click', function (event) {
		const user_id = this.getAttribute('data-user-id');
		let user_is_blocked = parseInt(this.getAttribute('data-user-blocked'));

		// Call the toggle_block function with the updated values
		toggle_block(event, user_id, user_is_blocked);

		// Toggle the user_is_blocked value
		user_is_blocked = user_is_blocked === 0 ? 1 : 0;
		// Update the data attribute with the new user_is_blocked value
		this.setAttribute('data-user-blocked', user_is_blocked);
	});
});

// gammel toggle_block med GET
// async function toggle_block(event, user_id, user_is_blocked) {
//     console.log(event.target)
//     console.log("user_id", user_id)
//     console.log("user_is_blocked", user_is_blocked)

//     const conn = await fetch (`/api/api-toggle-user-blocked.php?user_id=${user_id}&user_is_blocked=${user_is_blocked}`)

//     if (conn.ok) {
//       user_is_blocked = user_is_blocked == 0 ? 1 : 0

//       //   change icon to the correct one, upon changing it
//     // changes the icon in the span
//       event.target.parentElement.querySelector(".block-button-span").replaceChildren(user_is_blocked ? 'lock' : 'lock_open')

//     } else {
//       console.error("Error updating user status.")
//     }

//     console.log("user_is_blocked, after", user_is_blocked)

//     // why am i doing this?
//     const data = await conn.text()

//     console.log(data)

//   }

async function toggle_block(event, user_id, user_is_blocked) {
	const formData = new FormData();
	// adds the data from the div in the html, is this safe??
	formData.append('user_id', user_id);
	formData.append('user_is_blocked', user_is_blocked);

	const conn = await fetch('/api/api-toggle-user-blocked.php', {
		method: 'POST',
		body: formData,
	});

	if (conn.ok) {
		user_is_blocked = user_is_blocked == 0 ? 1 : 0;

		//   change icon to the correct one, upon changing it
		// changes the icon in the span
		event.target.parentElement
			.querySelector('.block-button-span')
			.replaceChildren(user_is_blocked ? 'lock' : 'lock_open');
	} else {
		console.error('Error updating user status.');
	}

	console.log('user_is_blocked, after', user_is_blocked);

	// why am i doing this?
	const data = await conn.text();

	console.log(data);
}

// async function start_timer() {
//     console.log(event.target.form);
//     my_timer = setTimeout(function() {
//       search_employees(event.target.form);
//     }, 2000);
//   }
var timer_search_users = null;

async function search_users(tag) {
	try {
		clearTimeout(timer_search_users);

		timer_search_users = setTimeout(async function () {
			const frm = document.getElementById('frm_search');
			// const url = frm.getAttribute('data-url')

			console.log('tag', `/api/api-search-${tag}.php`);

			console.log('searching...');

			console.log('form', frm);

			const conn = await fetch(`/api/api-search-${tag}.php`, {
				method: 'POST',
				body: new FormData(frm),
			});

			const data = await conn.json();

			console.log('data', data);
			document.querySelector('#query_results').innerHTML = null;

			// Customers and partners
			if (tag == 'customers' || tag == 'partners') {
				data.forEach((user) => {
					let div_user = `
                        <a href="/profile/${user.user_id}" class="grid grid-cols-3 p-2">
                            <div class="col-span-2">${user.user_name} ${user.user_last_name}</div>
                            <div class="col-span-1">${user.user_email}</div>
                        </a>
                    `;
					document
						.querySelector('#query_results')
						.insertAdjacentHTML('beforeend', div_user);
				});
			}
			// Employees
			if (tag == 'employees') {
				data.forEach((user) => {
					let div_user = `
                        <a href="/profile/${user.user_id}" class="grid grid-cols-[100fr,100fr,50fr] p-2">
                            <div class="">${user.user_name}</div>
                            <div class="">${user.user_last_name}</div>
                            <div class="">${user.employee_salary}</div>
                        </a>
                    `;
					document
						.querySelector('#query_results')
						.insertAdjacentHTML('beforeend', div_user);
				});
			}
			// Orders
			if (tag == 'orders') {
				data.forEach((order) => {
					var date = new Date(order.order_created_at * 1000);
					let div_order = `
                        <a href="/profile/${order.user_id}" class="grid grid-cols-[100fr,100fr,50fr] p-2">
                            <div class="">${order.user_name} ${order.user_last_name}</div>
                            <div class="">${order.order_items}</div>
                            <div class="">${order.user_email}</div>
                        </a>
                    `;
					document
						.querySelector('#query_results')
						.insertAdjacentHTML('beforeend', div_order);
				});
			}
		}, 500);
	} catch (error) {
		console.error(error);
	}
}

async function signup() {
	try {
		const frm = event.target;
		console.log(frm);
		const conn = await fetch('/api/api-signup.php', {
			method: 'POST',
			body: new FormData(frm),
		});

		const data = await conn.text();
		console.log(data);

		if (!conn.ok) {
			console.log(data);
			throw new Error(`something went wrong: ${data} `);
		}

		// TODO: redirect to the login page
		location.href = '/login';
	} catch (error) {
		alert(error);
	}
}
async function new_partner() {
	try {
		const frm = event.target;
		console.log(frm);
		const conn = await fetch('/api/api-new-partner.php', {
			method: 'POST',
			body: new FormData(frm),
		});

		const data = await conn.text();
		console.log(data);

		if (!conn.ok) {
			console.log(data);
			throw new Error(`something went wrong: ${data} `);
		}

		// TODO: redirect to the login page
		location.href = '/login';
	} catch (error) {
		alert(error);
	}
}

async function login() {
	try {
		const frm = event.target;
		console.log(frm);
		const conn = await fetch('/api/api-login.php', {
			method: 'POST',
			body: new FormData(frm),
		});

		const data = await conn.json();

		if (!conn.ok) {
			console.log(data);
			throw new Error(`something went wrong: ${data.info} `);
		}
		// api returns json redirect value with an url
		location.href = data['redirect'];
	} catch (error) {
		alert(error);
	}
}

async function update_user() {
	try {
		const frm = event.target;
		// console.log(frm)

		const conn = await fetch('/api/api-update-user.php', {
			method: 'POST',
			body: new FormData(frm),
		});

		const data = await conn.json();

		if (!conn.ok) {
			console.log(data);	
			throw new Error(`something went wrong: ${data.info} `);
		}
		console.log(data);
		// api returns json redirect value with an url
		// console.log(JSON.parse(data))
		location.href = data['redirect'];
	} catch (error) {
		alert(error);
	}
}

async function update_user_password() {
	console.log('made it to the update func');

	const frm = event.target;
	console.log(frm);

	const conn = await fetch('/api/api-update-user-password.php', {
		method: 'POST',
		body: new FormData(frm),
	});

	// const data = await conn.text()
	// console.log(data)

	if (!conn.ok) {
		console.log('something went wrong');
		return;
	}

	// TODO: redirect to profile page
}

// document.getElementById('btn_update_user_password').addEventListener('click',profile_button)
// document.getElementById('btn_update_user_info').addEventListener('click',profile_button)
// document.getElementById('btn_delete_user').addEventListener('click',profile_button)

function profile_button(event) {
	console.log('button pressed');
	// depending on target button, make other
	// document.getElementById('btn_update_user_info').classList.add('hidden')
	// document.getElementById('btn_update_user_password').classList.add('hidden')

	console.log(this.id);
	switch (event) {
		case 'btn_update_user_info':
			document
				.getElementById('frm_update_user_info')
				.classList.toggle('hidden');
			document.getElementById('frm_update_user_info').classList.toggle('flex');
			document
				.getElementById('frm_update_user_password')
				.classList.add('hidden');
			document.getElementById('frm_delete_user').classList.add('hidden');
			break;
		case 'btn_update_user_password':
			document
				.getElementById('frm_update_user_password')
				.classList.toggle('hidden');
			document
				.getElementById('frm_update_user_password')
				.classList.toggle('flex');
			document.getElementById('frm_update_user_info').classList.add('hidden');
			document.getElementById('frm_delete_user').classList.add('hidden');
			break;
		case 'btn_delete_user':
			document.getElementById('frm_delete_user').classList.toggle('hidden');
			document.getElementById('frm_delete_user').classList.toggle('flex');
			document.getElementById('frm_update_user_info').classList.add('hidden');
			document
				.getElementById('frm_update_user_password')
				.classList.add('hidden');
			break;
		// code block
	}
}

async function delete_user(event) {
	try {
		//All items in the row it is in
		const parent_div = event.currentTarget.parentElement.parentElement;
		// Get the delet button
		const delete_button =
			event.currentTarget.parentElement.parentElement.querySelector(
				'.btn_user_delete'
			);

		// UPDATE ICON
		let user_is_deleted = parseInt(
			delete_button.getAttribute('data-user-deleted')
		);
		console.log(parent_div.querySelector('.div_user_id').innerHTML);

		if (
			confirm(
				user_is_deleted != 0
					? 'Are you sure you want to restore this user'
					: 'Are you sure you want to delete this user'
			) != true
		) {
			return;
		}

		const formData = new FormData();
		// adds the data from the div in the html, is this safe??
		formData.append(
			'user_id',
			parent_div.querySelector('.div_user_id').innerHTML
		);

		const conn = await fetch('/api/api-delete-user.php', {
			method: 'POST',
			body: formData,
		});

		if (!conn.ok) {
			console.log('something went wrong');
			console.error('Error deleting user.');
			return;
		}

		const data = await conn.text();
		console.log(data);

		// UPDATE ICON
		user_is_deleted = user_is_deleted == 0 ? 1 : 0;
		delete_button.setAttribute('data-user-deleted', user_is_deleted);

		// get the span inside the button
		// Toggle the icon based on user_is_deleted
		delete_button
			.querySelector('span')
			.replaceChildren(user_is_deleted ? 'settings_backup_restore' : 'delete');
	} catch (error) {
		// Handle any unexpected errors
		console.error('An error occurred:', error);
	}
}

document
	.getElementById('btn_sidebar')
	.addEventListener('click', toggle_sidebar);
function toggle_sidebar() {
	// document.getElementById('sidebar').classList.toggle('right-0')
	// document.getElementById('sidebar').classList.toggle('left-0')
	document.getElementById('sidebar').classList.toggle('translate-x-full');
	document.getElementById('sidebar').classList.toggle('-translate-x-full');
}
// ########################
// Color
const root = document.querySelector(':root');
// check if there already is a pref stored
// if not, define based on device prefered color scheme
if (localStorage.getItem('theme_preference') === null) {
	if (window.matchMedia('(prefers-color-scheme: dark').matches) {
		localStorage.setItem('theme_preference', 'dark');
		root.setAttribute('data-theme', 'dark');
		document.getElementById('btn_change_theme').innerHTML = 'dark_mode';
	} else {
		localStorage.setItem('theme_preference', 'light');
		root.setAttribute('data-theme', 'light');
		document.getElementById('btn_change_theme').innerHTML = 'light_mode';
	}
	// if there is a pref stored, use that
} else {
	root.setAttribute('data-theme', localStorage.getItem('theme_preference'));
	// lav theme icon inner html
	let theme_icon = root.getAttribute('data-theme');
	theme_icon += '_mode';
	document.getElementById('btn_change_theme').innerHTML = theme_icon;
}

function toggle_theme() {
	if (root.getAttribute('data-theme') != 'dark') {
		root.setAttribute('data-theme', 'dark');
		localStorage.setItem('theme_preference', 'dark');
		document.getElementById('btn_change_theme').innerHTML = 'dark_mode';
		return;
	}
	root.setAttribute('data-theme', 'light');
	localStorage.setItem('theme_preference', 'light');
	document.getElementById('btn_change_theme').innerHTML = 'light_mode';

	// TODO ?? change icon
}

// ########################
// Language
// if (localStorage.getItem('language_preference') === null) {
//   root.setAttribute('data-language', 'en')
// } else {
//   // hvis getitem ikke er tom, så tager vi den preference de har og indsætter den i language
//   root.setAttribute('data-language', localStorage.getItem('language_preference'))
// }

// add this onclick to button in _header.php
//  onclick="toggle_language('<?php out($shortcode) ?>')"

// function toggle_language(new_language) {
//   console.log(new_language);
//   console.log('newlanguagecalled');
//   if (new_language == localStorage.getItem('language_preference')){
//     return;
//   }

//   const allowed_languages = ['en', 'da', 'it', 'es', 'de'];

//   if (!allowed_languages.includes(new_language)) {
//     return;
//   }

//   localStorage.setItem("language_preference", new_language)
//   root.setAttribute('data-language', new_language)

// console.log(localStorage.getItem('language_preference'))
// console.log(root.getAttribute('data-language'))
// }

function toggle_language_dropdown() {
	if (
		document.querySelector('#language_dropdown').classList.contains('hidden')
	) {
		// dropdown
		document.querySelector('#language_dropdown').classList.remove('hidden');
		document.querySelector('#language_dropdown').classList.add('flex');
		document.querySelector('#language_dropdown').classList.add('bg-bkg-2');

		// backdrop
		document
			.querySelector('#language_button_background')
			.classList.toggle('rounded-xl');
		document
			.querySelector('#language_button_background')
			.classList.toggle('rounded-t-xl');
		document
			.querySelector('#language_button_background')
			.classList.toggle('bg-bkg-2');
		return;
	}
	document.querySelector('#language_dropdown').classList.add('hidden');
	document.querySelector('#language_dropdown').classList.remove('flex');

	document
		.querySelector('#language_button_background')
		.classList.toggle('bg-bkg-2');
	// document.querySelector("#language_dropdown").classList.remove("bg-bkg-2")
	document
		.querySelector('#language_button_background')
		.classList.toggle('rounded-xl');
	document
		.querySelector('#language_button_background')
		.classList.toggle('rounded-t-xl');
}



async function add_to_cart(item_id){
	try {
		console.log(item_id)
		// if item_id is in localstorage - +1 amount
	
	const conn = await fetch(`/api/api-add-to-cart.php?item_id=${item_id}`);

	const data = await conn.json()
	// const data = await conn.text()
	// console.log(data)

	if (!conn.ok) {
		console.log('something went wrong');
		return;
	}

	console.log(data)

	} catch (error) {
		console.log(error)
	}
}

async function raise(amount, user_id, curr_sal) {

	try {
		console.log(amount)
		// if item_id is in localstorage - +1 amount
	
	const conn = await fetch(`/api/api-give-raise.php?amount=${amount}&user_id=${user_id}&curr_sal=${curr_sal}`);

	const data = await conn.json()
	// const data = await conn.text()
	// console.log(data)

	if (!conn.ok) {
		console.log('something went wrong');
		return;
	}

	console.log(data)

	} catch (error) {
		console.log(error)
	}

}