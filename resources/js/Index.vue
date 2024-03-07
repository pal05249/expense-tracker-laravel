<template>
	<div class="wrapper">
		<nav class="navbar navbar-light justify-content-end">
			<!-- <router-link class="navbar-brand mr-auto" to="/dashboard">Dashboard</router-link> -->
			<button class="btn text-right" @click="logout">Logout</button>
		</nav>
		<aside class="main-sidebar sidebar-dark-primary elevation-4">
			<!-- Brand Logo -->
			<a href="index3.html" class="brand-link">

				<span class="brand-text font-weight-light">AdminLTE 3</span>
			</a>

			<!-- Sidebar -->
			<div class="sidebar">
				<!-- Sidebar user panel (optional) -->
				<div class="user-panel mt-3 pb-3 mb-3 d-flex">
					<div class="image">
						<img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
					</div>
					<div class="info">
						<a href="#" class="d-block">Alexander Pierce</a>
					</div>
				</div>

				<!-- Sidebar Menu -->
				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
						<!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->

						<li class="nav-item">
							<router-link to="/dashboard" class="nav-link">
								<i class="nav-icon fas fa-th"></i>
								<p>
									Dashboard
								</p>
							</router-link>
						</li>
						<li class="nav-item">
							<router-link to="/transactions" class="nav-link">
								<i class="nav-icon fas fa-th"></i>
								<p>
									Transactions
								</p>
							</router-link>
						</li>
					</ul>
				</nav>
				<!-- /.sidebar-menu -->
			</div>
			<!-- /.sidebar -->
		</aside>
		<div class="content-wrapper">
			<router-view></router-view>
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {};
	},
	methods: {
		logout() {
			axios.post("/logout").then((response) => {
				window.location.href = "/login";
			});
		},
	},
};
</script>

<style></style>