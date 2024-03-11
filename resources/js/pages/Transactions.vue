<template>
  <div>
    <div class="card-box">
      <div class="row align-items-center mb-3">
        <div class="col">
          <h4 class="mt-0 header-title">Transactions</h4>
          <p class="text-muted font-14 mb-0">Manage your expense and incomes</p>
        </div>
        <div class="col-auto">
          <ul class="nav nav-pills">
            <li class="nav-item">
              <a href="create-user.html" @click.prevent="setActive('list')" :class="{ active: isActive('list') }"
                class="nav-link">
                List
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" @click.prevent="setActive('create'); clearForm()"
                :class="{ active: isActive('create') }" href="#profile">{{ this.update ? "Edit" : "Create" }}</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="tab-content border-0" id="myTabContent">
        <div class="tab-pane fade" :class="{ 'active show': isActive('list') }" id="list">
          <div class="dataTables_wrapper no-footer">
            <div class="row">
              <div class="col-sm-12 col-md-6">
                <div class="dataTables_filter" id="paginate">
                  <label>Show
                    <select name="paginate" v-model="paginate" aria-controls="paginate" class="custom-select">
                      <option value="5">5</option>
                      <option value="10">10</option>
                      <option value="20">20</option>
                      <option v-if="transaction?.data.length != 0" :value="transactions.total">All</option>
                    </select>
                    entries</label>
                </div>
              </div>
              <div class="col-sm-12 col-md-6">
                <div id="search " class="dataTables_filter float-right">
                  <label>Search:<input type="search" v-model="search" class="form-control" placeholder="Search here..."
                      aria-controls="search" @input="$helpers.debounce(this.searchFilter)" />
                  </label>
                </div>
              </div>
            </div>
            <div class="row align-items-center justify-content-between">
              <div class="col-sm-12 col-md-4 mt-3">
                <div id="userManagement_filter" class="dataTables_filter" style="width: 100%">
                  <label class="w-100">Filter by Category </label>
                  <div style="background: #fff;">
                    <v-select :options="this.categories" label="category_name" :reduce="(item) => item.id"
                      v-model="filter.categoryId" />
                    <span class="text-danger">
                      <ul class="list-unstyled">
                        <li v-for="(error) in this.Error?.categoryId" :key="error">{{ error }}</li>
                      </ul>
                    </span>
                  </div>

                </div>
              </div>

              <div class="col-sm-12 col-md-4">
                <div id="date-filter" class="dataTables_filter" style="width: 100%">
                  <label for="validationDefault01" class="font-weight-bold ">From date</label>
                  <VueDatePicker v-model="this.filter.fromDate" required placeholder="dd/mm/yyyy" format="dd/MM/yyyy"
                    autoApply></VueDatePicker>
                </div>
              </div>
              <div class="col-sm-12 col-md-4">
                <div id="date-filter" class="dataTables_filter" style="width: 100%">
                  <label for="validationDefault01" class="font-weight-bold ">To date</label>
                  <VueDatePicker v-model="this.filter.toDate" required placeholder="dd/mm/yyyy" format="dd/MM/yyyy"
                    autoApply></VueDatePicker>
                </div>
              </div>

            </div>
            <!-- <table class="table table-bordered  table-hover table-active"> -->
            <div class="table-responsive mt-2">
              <div v-if="loading == true" class="row text-center">
                <div class="col">
                  <loading-bar />
                </div>
              </div>
              <table class="table" v-else>
                <thead class="thead-light">
                  <tr>
                    <th>Sl No</th>
                    <th>
                      Category
                    </th>
                    <th>Description</th>
                    <th>
                      <a href="#" @click.prevent="change_sort('amount')">Amount</a>
                      <i class="fa" v-if="sortField == 'amount'" :class="sortDirection == 'asc'
                ? 'fa-sort-asc'
                : 'fa-sort-desc'
                "></i>
                      <i v-else class="fa fa-sort"></i>
                    </th>
                    <th> <a href="#" @click.prevent="change_sort('date_of_expense')">Date of expense</a>
                      <i class="fa" v-if="sortField == 'date_of_expense'" :class="sortDirection == 'asc'
                ? 'fa-sort-asc'
                : 'fa-sort-desc'
                "></i>
                      <i v-else class="fa fa-sort"></i>
                    </th>
                    <th>Edit</th>
                    <th>Delete</th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-if="transactions?.data?.length == 0" class="text-secondary text-center p-1" role="alert">
                    <td colspan="7">
                      <i class="fa fa-alert"></i>
                      No records found
                    </td>
                  </tr>
                  <tr v-for="(item, index) in transactions.data" :key="item.id">

                    <td>{{ transactions.from + index }}</td>
                    <td>{{ item.category.category_name }}</td>
                    <td>{{ item.description }}</td>
                    <td>{{ item.amount }}</td>
                    <td>{{ item.date_of_expense }}</td>

                    <td>
                      <a @click.prevent="setActive('create')" :class="{
                active: isActive('create'),
              }" href="#profile"><button class="btn btn-warning" @click="fetchDataForUpdate(item.id)">
                          <i class="fa fa-edit"> </i></button></a>
                    </td>
                    <td>
                      <button class="btn btn-danger" @click="deleteTransaction(item.id)">
                        <i class="fa fa-trash"> </i></button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="row">
              <div class="col-sm-12 col-md-6">
                <div class="dataTables_info font-weight-bold" id="pageCount" role="status" aria-live="polite">
                  Showing
                  {{ transactions.from ? transactions.from : 0 }}
                  to
                  {{ transactions.to ? transactions.to : 0 }} of
                  {{ transactions.total ? transactions.total : 0 }}
                  entries
                </div>
              </div>

              <div class="col-sm-12 col-md-6">
                <pagination :pagination="transactions" @paginate="loadTransactions()" :offset="4">
                </pagination>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 col-md-6">
                <div class="font-weight-bold" id="totalExpenses" role="status" aria-live="polite">
                  Summary: Total expense is <i class="fa fa-rupee"></i>{{ this.totalExpenses }}
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" :class="{ 'active show': isActive('create') }" id="create">
          <form @submit.prevent="onSubmit">
            <div class="row">
              <div class="col-md-6 col-xl-4">
                <div class="form-group">
                  <label for="validationDefault01" class="font-weight-bold ">Category<span class="text-danger">*</span>
                    <svg data-toggle="modal" data-target="#exampleModalVariable" data-whatever="@mdo"
                      @click="this.clearCategoryForm" style="width: 20px; height: 20px; cursor: pointer;"
                      viewBox="0 0 24 24">
                      <path fill="green"
                        d="M17,13H13V17H11V13H7V11H11V7H13V11H17M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3Z" />
                    </svg></label>
                  <div style="background: #fff;">
                    <v-select :options="this.categories" label="category_name" :reduce="(item) => item.id"
                      v-model="form.categoryId" />
                    <span class="text-danger">
                      <ul class="list-unstyled">
                        <li v-for="(error) in this.Error?.categoryId" :key="error">{{ error }}</li>
                      </ul>
                    </span>
                  </div>
                </div>


                <div class="form-group">
                  <label for="validationDefault01" class="font-weight-bold ">Amount<span
                      class="text-danger">*</span></label>
                  <input ref="amount" type="text" placeholder="Enter amount" v-model="form.amount" class="form-control"
                    id="amount" required @invalid="invalidateForm" />

                  <span class="text-danger">
                    <ul class="list-unstyled">
                      <li v-for="(error) in this.Error?.amount" :key="error">{{ error }}</li>
                    </ul>
                  </span>
                </div>

                <div class="form-group">
                  <label for="validationDefault01" class="font-weight-bold ">Description <span
                      class="text-danger">*</span></label>

                  <input type="text" placeholder="What was this spent for?" v-model="form.description"
                    class="form-control" id="department" required @invalid="invalidateForm" />

                  <span class="text-danger">
                    <ul class="list-unstyled">
                      <li v-for="(error) in this.Error?.description" :key="error">{{ error }}</li>
                    </ul>
                  </span>
                </div>
                <div class="form-group">
                  <label for="validationDefault01" class="font-weight-bold ">Date of transaction<span
                      class="text-danger">*</span></label>
                  <VueDatePicker v-model="form.dateOfExpense" required placeholder="dd/mm/yyyy" format="dd/MM/yyyy"
                    autoApply></VueDatePicker>
                </div>
                <div class="form-group">
                  <div class="alert alert-success alert-dismissible mb-1" role="alert" v-if="showAlert == true">
                    {{ message }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <button v-if="update === false" type="submit" class="btn btn-success mr-3">
                    Add
                  </button>

                  <button v-if="update === true" ref="addbutton" type="button" @click="updateTransaction(this.id)"
                    class="btn btn-success mr-3">
                    update
                  </button>
                  <a @click.prevent="setActive('list')">
                    <button type="button" @click="RevertToInitialState()" class="btn btn-light">
                      Cancel
                    </button>
                  </a>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="exampleModalVariable" tabindex="-1" role="dialog" aria-labelledby="exampleModalVariable"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="height: auto">
        <div class="modal-content" style="width: 100%">
          <div class="modal-header bg-success">
            <h5 class="modal-title text-white" id="exampleModalVariable">Add Categories</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="addCategory()">
              <div style="padding-left: 0px; height: auto">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="validationDefault01" class="font-weight-bold">Category Name
                        <span class="text-danger">*</span>
                      </label>
                      <input ref="category" type="text" placeholder="Enter the category name" v-model="categoryName"
                        class="form-control " required>


                      <span class="text-danger">
                        <ul class="list-unstyled">
                          <li v-for="(error) in this.Error?.category_name" :key="error">{{ error }}</li>
                        </ul>
                      </span>


                    </div>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible mb-1" role="alert"
                      v-if="showCategoryAlert == true">
                      {{ message }}
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <button v-if="updateCategory === false" type="button" class="btn btn-success" style=""
                      @click="addCategory()">
                      Add
                    </button>
                    <button v-if="updateCategory === true" ref="addbutton" type="button" @click="updateCategoryName()"
                      class="btn btn-success mr-3">
                      Update
                    </button>
                    <button v-if="updateCategory === true" ref="addbutton" type="button" @click="RevertToInitialState()"
                      class="btn btn-light" style="">
                      Cancel
                    </button>
                  </div>
                </div>
              </div>
            </form>
            <table class="table">
              <thead class="thead-light">
                <tr>
                  <th scope="col">Sl No</th>
                  <th scope="col">Category</th>
                  <th scope="col">Edit</th>
                  <th scope="col">Delete</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in categories" :key="item.id" :value="item.id">
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.category_name }}</td>
                  <td>
                    <button class="btn btn-warning" @click="fetchDataForCategories(item.id)">
                      <a>
                        <i class="fa fa-edit"></i>
                      </a>
                    </button>
                  </td>
                  <td>
                    <button class="btn btn-danger" @click="deleteCategory(item.id)">
                      <a>
                        <i class="fa fa-trash"></i>
                      </a>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>




</template>

<script>
import Pagination from '../components/Pagination'
import LoadingBar from '../components/LoadingBar'
import VueDatePicker from '@vuepic/vue-datepicker';

export default {
  name: "Transactions",

  data: () => ({
    activeItem: "list",
    paginate: 5,
    search: "",
    errors: false,
    update: false,
    loading: "",
    id: "",
    totalExpenses: 0,
    showAlert: false,
    showCategoryAlert: false,
    message: "",
    Error: "",
    sortField: "date_of_expense",
    sortDirection: "desc",
    transactions: {
      total: 5,
      per_page: 5,
      last_page: 1,
      from: 1,
      to: 1,
      current_page: 1,
      data: []
    },
    categoryName: "",
    updateCategory: false,
    categories: [],
    form: {
      dateOfExpense: new Date(),
      categoryId: "",
      description: "",
      amount: 0
    },
    filter: {
      categoryId: "",
      fromDate: "",
      toDate: ""
    },
    offset: 5,
  }),
  props: {
    errors: Object,
  },
  components: { LoadingBar, Pagination, VueDatePicker },
  created() {
    this.loadTransactions();
    this.loadCategories()
  },
  watch: {
    paginate: function () {
      this.fetchData()
    },
  },
  watch: {
    'filter.categoryId': function () {
        this.fetchData()
    },
    'filter.fromDate': function () {
        this.fetchData()
    },
    'filter.toDate': function () {
        this.fetchData()
    }
  },
  methods: {
    fetchData() {
      this.transactions.current_page = 1;
      this.loadTransactions();
    },
    clearCategoryForm() {
      this.updateCategory = false;
      this.categoryName = ""
    },
    isActive(menuItem) {
      return this.activeItem === menuItem;
    },
    setActive(menuItem) {
      this.activeItem = menuItem;
      if (menuItem == "list") this.RevertToInitialState();
    },
    searchFilter() {
      this.transactions.current_page = 1;
      this.loadTransactions();
    },
    RevertToInitialState() {
      this.update = false
      this.clearForm();
    },
    change_sort(field) {
      this.sortField = field;
      this.sortDirection = this.sortDirection == "asc" ? "desc" : "asc";

      this.loadTransactions();
    },
    loadTransactions() {
      this.loading = true;
      axios
        .get(
          `expenses?q=${this.search}&paginate=${this.paginate}&page=${this.transactions.current_page}&sortDirection=${this.sortDirection}&sortField=${this.sortField}&categoryId=${this.filter.categoryId??''}&fromDate=${this.$helpers.convertDateToString(this.filter.fromDate)}&toDate=${this.$helpers.convertDateToString(this.filter.fromDate)}`
        )
        .then((response) => {
          this.loading = false;
          this.transactions = response.data.expenses;
          this.totalExpenses = response.data.totalAmount;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    loadCategories() {
      axios
        .get(
          `/categories`
        )
        .then((response) => {
          this.loading = false;
          this.categories = response.data.categories;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    fetchDataForUpdate(value) {
      const result = this.transactions.data.filter((data) => data.id == value);
      this.form.amount = result[0].amount;
      this.form.description = result[0].description;
      this.form.dateOfExpense = new Date(result[0].date_of_expense);
      this.form.categoryId = result[0].category_id;
      this.update = true;
      this.id = value;
      this.$refs.form.amount.focus();
    },

    fetchDataForCategories(id) {
      const result = this.categories.filter((data) => data.id == id);
      this.categoryName = result[0].category_name;
      this.updateCategory = true;
      this.id = id;
      this.$refs.category.focus();
    },
    updateTransaction(expenseId) {
      axios
        .put(`/expenses/${expenseId}`, {
          amount: this.form.amount.trim(),
          dateOfExpense: this.form.dateOfExpense.toISOString().split('T')[0],
          categoryId: this.form.categoryId,
          description: this.form.description.trim()
        })
        .then((response) => {
          this.loadTransactions();
          this.RevertToInitialState();
          this.message = "Updated successfully";
          this.showAlert = true;
          setTimeout(() => {
            this.showAlert = false;
          }, 1000);
        })
        .catch((error) => {
          if (error.status == 401) {
            alert(error.data);
          }
          if (error.response.status === 422) {
            this.Error = error.response.data.errors
          }
          if (error)
            setTimeout(() => {
              this.Error = "";
            }, 5000);
        });
    },
    deleteTransaction(expenseId) {
      axios
        .delete(`/expenses/${expenseId}`)
        .then((response) => {
          this.loadTransactions();
          this.message = "Deleted successfully";
          this.showAlert = true;
          setTimeout(() => {
            this.showAlert = false;
          }, 1000);
        })
        .catch((error) => {
          if (error.status == 401) {
            alert(error.data);
          } if (error.response.status === 422) {
            this.Error = error.response.data.errors
          }
          if (error)
            setTimeout(() => {
              this.Error = "";
            }, 5000);
        });
    },
    addCategory() {
      axios
        .post("/categories", { category_name: this.categoryName.trim() })
        .then((response) => {
          this.loadCategories();
          this.clearCategoryForm();
          this.message = "Added successfully";
          this.showCategoryAlert = true;
          setTimeout(() => {
            this.showCategoryAlert = false;
          }, 1000);
        })
        .catch((error) => {

          if (error.status == 401) {
            alert(error.data);
          } if (error.response.status === 422) {
            this.Error = error.response.data.errors
            console.log(this.Error)
          }

          if (error)
            setTimeout(() => {
              this.Error = "";
            }, 5000);
        });
      this.loading = false;

    },
    updateCategoryName() {
      axios
        .put(`/categories/${this.id}`, { category_name: this.categoryName.trim() })
        .then((response) => {
          this.loadCategories();
          this.clearCategoryForm();
          this.message = "Updated successfully";
          this.showCategoryAlert = true;
          setTimeout(() => {
            this.showCategoryAlert = false;
          }, 1000);
        })

        .catch((error) => {
          if (error.status == 401) {
            alert(error.data);
          } if (error.response.status === 422) {
            this.Error = error.response.data.errors
          }
          if (error)
            setTimeout(() => {
              this.Error = "";
            }, 5000);
        });
      this.loading = false;
    },
    deleteCategory(id) {
      axios
        .delete(`/categories/${id}`)
        .then((response) => {
          this.loadCategories();
          this.clearCategoryForm();
          this.message = "Deleted successfully";
          this.showCategoryAlert = true;
          setTimeout(() => {
            this.showCategoryAlert = false;
          }, 1000);
        })
        .catch((error) => {
          console.log(error)
          if (error.status == 401) {
            alert(error.data);
          } if (error.response.status === 422) {
            this.Error = error.response.data.errors
          }
          if (error)
            setTimeout(() => {
              this.Error = "";
            }, 5000);
        });
      this.loading = false;
    },
    invalidateForm() {
      this.errors = true;
    },

    onSubmit() {
      if (this.update === true) {
        this.UpdateData();
      } else {
        const formData = new FormData();

        formData.append("amount", this.form.amount.trim());
        formData.append("description", this.form.description.trim());
        formData.append("dateOfExpense", this.form.dateOfExpense.toISOString().split('T')[0]);
        formData.append("categoryId", this.form.categoryId);

        axios
          .post("/expenses", formData)
          .then((response) => {
            this.loadTransactions();
            this.clearForm();
            this.message = "Added successfully";
            this.showAlert = true;
            setTimeout(() => {
              this.showAlert = false;
            }, 1000);
          })

          .catch((error) => {
            if (error.status == 401) {
              alert(error.data);
            } if (error.response.status === 422) {
              this.Error = error.response.data.errors
            }
            if (error)
              setTimeout(() => {
                this.Error = "";
              }, 5000);
          });
        this.loading = false;
      }
    },
    clearForm() {
      this.form.amount = "";
      this.form.dateOfExpense = new Date();
      this.form.description = ""
      this.form.categoryId = ""
    },
  },
};
</script>