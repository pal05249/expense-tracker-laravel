<template>
  <!-- Main Sidebar Container -->
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div>
        </div>
      </div>
    </div>


    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-3 col-6" v-for="card in cardDetails">
            <CountCard :count="card.count" :background-class="card.backgroundClass" :text="card.text" :icon="card.icon">
            </CountCard>
          </div>
        </div>
        <div class="row">
          <CategoryWiseGraphVue></CategoryWiseGraphVue>
        </div>
      </div>
    </section>

  </div>



</template>

<script>
import CategoryWiseGraphVue from "../components/CategoryWiseGraph.vue";
import CountCard from "../components/CountCard";
export default {
  components: {
    CountCard, CategoryWiseGraphVue
  },
  mounted() {
    this.fetchCardsDetails()
  },
  data() {
    return {
      cardDetails: [],
    }
  },
  methods: {
    fetchCardsDetails() {
      axios.get(`/dashboard-details?route-use=cardDetails`).then((response) => {
        let { totalTransactionsCount, totalExpenseAmount, totalExpenseForCurrentMonth } = response.data
        this.cardDetails = [{
          count: totalTransactionsCount,
          backgroundClass: "bg-primary",
          text: 'Total Transactions',
          icon: "fa-exchange"
        }, {
          count: totalExpenseAmount,
          backgroundClass: "bg-danger",
          text: 'Total Expenses',
          icon: "fa-money"
        }, {
          count: totalExpenseForCurrentMonth,
          backgroundClass: "bg-warning",
          text: 'Current month expenses',
          icon: "fa-money"
        }]
      })
    }
    , 
  }
};
</script>
