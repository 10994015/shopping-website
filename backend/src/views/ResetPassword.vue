<script setup>

</script>

<template>
<div class="login">
    <div class="darken"></div>
    <div class="main">
        <h1>設定新密碼</h1>
        <form @submit.prevent="">
            <div class="form-group">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <input type="password" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <input type="password" name="password-repeat" placeholder="Password Repeat">
            </div>
            <div class="commit-text">
                <router-link :to="{name: 'login'}">Go back to login?</router-link>
            </div>
            <button type="submit">Submit</button>
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
            font-size: 24px;
            letter-spacing: 2px;
            color:#fff;
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
            >.commit-text{
                color:#ccc;
                font-size: 14px;
                margin-right: auto;
                transition: .3s;
                &:hover{
                    color:#fff;
                }
            }
            >button{
                margin-top: 20px;
                background-color: rgba(28, 132, 238,.85);
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