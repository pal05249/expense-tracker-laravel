<template>
    <div class="small-box" :class="backgroundClass">
     <div class="inner">
         <h4><i v-if="text!='Total Transactions'" class="fa fa-rupee"></i>{{ count }}</h4>
            <p>{{ text }}</p>
        </div>
        <div class="icon">
            <i class="fa" :class="icon"></i>
        </div>
        <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
    </div>

</template>

<script>
export default {
    data() {
        return {

        }
    },
    props:{
        count:Number,
        text:String,
        backgroundClass:String,
        icon:String
    }
}

</script>