<template>
    <div class="small-box bg-info">
        <div class="inner">
            <h3>{{ count }}</h3>
            <p>{{ text }}</p>
        </div>
        <div class="icon">
            <i class="icon icon-bag"></i>
        </div>
        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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
        text:String
    }
}

</script>