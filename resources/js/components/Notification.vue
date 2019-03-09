<template>
	<li v-if="notifications.length > 0" class="nav-item dropdown">
		<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
			<i id="bell" class="far fa-bell d-inline"></i>
		</a>

		<div class="dropdown-menu dropdown-menu-right">
			<a v-for="note in notifications"
			class="dropdown-item"
			:href="note.data.url"
			v-text="note.data.description"
			@click="markAsRead(note)"></a>

			<a class="dropdown-item text-right"
			href="#"
			@click="markAllAsRead()"><b>Clear</b></a>
		</div>
	</li>
</template>

<script>
export default {
	data() {
		return {
			notifications:[]
		}
	},
	methods: {
		markAsRead(notification) {
			axios.delete('/notifications/'+notification.id)
		},
		markAllAsRead() {
			axios.delete('/notifications/all')
			.then(response => {
				this.notifications = [];
			})
		}
	},
	created() {
		axios.get('/notifications')
		.then(response => {
			this.notifications = response.data;
			console.log(response);
		})
	}
}
</script>

<style>
#bell {
	font-size:30px;
	padding:10px;
}
</style>
