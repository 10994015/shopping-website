<script setup>

</script>

<template>
    <div class="main">
        <h2>404!</h2>
        <h6>SORRY, PAGE NOT FOUND</h6>
        <p>It will be as simple as Occidental in fact, it will Occidental to an English person</p>
        <router-link :to="{name:'app.dashboard'}">Back to Dashboard</router-link>
    </div>
</template>

<style lang="scss" scoped>
.main{
    width: 100%;
    height: 100%;
    min-height: 100vh;
    background-color: #F4F5F8;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    >h2{
        text-transform: uppercase;
        background: repeating-linear-gradient(45deg,#1c84ee,#1c84ee 20px,#34c38f 40px,#34c38f 10px);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 12rem;
        line-height: .8;
        position: relative;
        font-weight: 900;
        text-align: center;
    }
    >h6{
        font-weight: 900;
        margin-top: 35px;
        text-align: center;
    }
    >p{
        color:#555;
        margin-top: 35px;
        text-align: center;
    }
    >a{
        width:300px;
        height: 45px;
        background-color: #1c84ee;
        color:#fff;
        text-align: center;
        line-height: 45px;
        border-radius: 30px;
        margin-top: 35px;
    }
}
</style>