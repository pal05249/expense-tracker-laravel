<template>
	<div class="wrapper">
		<nav class="main-header navbar navbar-expand navbar-white navbar-light">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fa fa-bars"></i></a>
				</li>
			</ul>
			<!-- Right navbar links -->
			<ul class="navbar-nav ml-auto">
				<!-- Messages Dropdown Menu -->
				<li class="nav-item dropdown">
					<a class="nav-link" data-toggle="dropdown" href="#">
						<i class="fa fa-user-circle"></i> {{ loggedInUserDetails.name }}
					</a>
					<div class="dropdown-menu text-center dropdown-menu dropdown-menu-right">
						<span class="dropdown-header">Welcome {{ loggedInUserDetails.name }}!</span>
						<div class="dropdown-divider"></div>
						<router-link :to="{ path: '/profile', params: { name: this.loggedInUserDetails.name } }"
							class="dropdown-item">
							<i class="fa fa-address-card mr-2"></i> My profile
						</router-link>
						<div class="dropdown-divider"></div>
						<a href="#" @click="logout" class="dropdown-item">
							<i class="fa fa-arrow-right"></i> Logout

						</a>

					</div>
				</li>
				<li class="nav-item">

				</li>
			</ul>
		</nav>
		<aside class="main-sidebar sidebar-dark-primary elevation-4">
			<!-- Brand Logo -->
			<router-link to="/dashboard" class="brand-link">

				<span class="brand-text font-weight-light">Expenso</span>
			</router-link>

			<!-- Sidebar -->
			<div class="sidebar">
				<!-- Sidebar user panel (optional) -->
				<div class="user-panel my-3 pb-3 pl-2 d-flex align-items-center justify-content-start">
					<div class="image">
						<i class="fa fa-user-circle text-white"></i>
					</div>
					<div class="info">
						<router-link to="/profile" class="d-block">{{ loggedInUserDetails.name }}</router-link>
					</div>
				</div>

				<!-- Sidebar Menu -->
				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
						<!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->

						<li class="nav-item">
							<router-link to="/dashboard" class="nav-link">
								<i class="nav-icon fa fa-th"></i>
								<p>
									Dashboard
								</p>
							</router-link>
						</li>
						<li class="nav-item">
							<router-link to="/transactions" class="nav-link">
								<i class="nav-icon fa fa-money"></i>
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
		<div class="content-wrapper bg-white p-4">
			<router-view></router-view>
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {
			loggedInUserDetails: {}
		};
	},
	mounted() {
		this.fetchLoggedInUserDetails()
	},
	methods: {
		logout() {
			axios.post("/logout").then((response) => {
				window.location.href = "/login";
			});
		},
		fetchLoggedInUserDetails() {
			axios.get("/logged-in-user-details").then((response) => {
				this.loggedInUserDetails = response.data.userDetails;
			}).catch((err) => {
				console.log(err)
			})
		}
	},
};
</script>
