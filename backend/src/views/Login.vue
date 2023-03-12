<script setup>

</script>

<template>
<div class="login">
    <div class="darken"></div>
    <div class="main">
        <h1>FZR</h1>
        <form @submit.prevent="login">
            <div class="form-group">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
                <input type="text" placeholder="Email">
            </div>
            <div class="form-group">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>

                </div>
                <input type="password" placeholder="Password">
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</div>
    
</template>

<style lang="scss" scoped>
.login{
    width: 100%;
    height: 100%;
    min-height: 100vh;
    background-image: url('../images/bg13.jpg');
    display: flex;
    justify-content: center;
    align-items: center;
    .darken{
        position: absolute;
        top: 0;
        left:0;
        width:100%;
        height: 100%;
        background-color: rgba($color: #000000, $alpha: 0.75);
        z-index: 1;
    }
    .main{
        position: relative;
        z-index: 10;
        color:#fff;
        display: flex;
        flex-direction: column;
        align-items: center;
        h1{
            font-weight: 900;
            font-size: 44px;
            letter-spacing: 2px;
            color:#fff;
            text-shadow: 0.2em 0.1em 0.2em rgba(28, 132, 238,.75);
        }
        form{
            display: flex;
            flex-direction: column;
            align-items: center;
            margin:30px 0;
            >.form-group{
                display: flex;
                margin-bottom: 10px;
                .icon{
                    background-color: hsla(0,0%,100%,.1);
                    width: 36px;
                    height: 45px;
                    border:none;
                    padding: 15px 0 15px 19px;
                    border-radius: 30px 0 0 30px;
                    margin-right: 0;
                }
                input{
                    background-color: hsla(0,0%,100%,.1);
                    width:274px;
                    height: 45px;
                    margin-bottom: 10px;
                    border-radius: 30px;
                    border-top-left-radius: 0;
                    border-bottom-left-radius: 0;
                    border-left: 0;
                    outline: none;
                    padding: 15px 18px 15px 16px;
                    font-size: 14px;
                }
            }
            >button{
                margin-top: 30px;
                background-color: rgba(28, 132, 238,.75);
                width:300px;
                height: 45px;
                border-radius: 30px;
                font-size: 14px;
                transition: .3s;
                &:hover{
                    background-color: rgba(28, 132, 238,1);
                }
            }
            
        }
    }
}
</style>