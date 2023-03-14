<script setup>
import { ref } from 'vue';
import {useRouter} from 'vue-router';
import store from "../store"

const router = useRouter();

const loading = ref(false);
const errorMsg = ref('');

const user = {
    'email':'',
    'password':'',
    'remember': false,
}

const login = () =>{
    loading.value = true;
    store.dispatch('login', user).then(()=>{
        loading.value = false;
        router.push({name:'app.dashboard'});

    }).catch(({response})=>{
        loading.value = false;
        errorMsg.value = response.data.message;
    })
};
</script>

<template>
<div class="login">
    <div class="darken"></div>
    <div class="main">
        <h1>FZR CMS</h1>
        <form @submit.prevent="login">
            <div class="form-group">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
                <input type="email" name="email" placeholder="Email" v-model="user.email">
            </div>
            <div class="form-group">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <input type="password" name="password" placeholder="Password" v-model="user.password">
            </div>
            <div class="commit-text">
                <label for="remember">
                    <input type="checkbox" v-model="user.remember" id="remember" />
                    記住我
                </label>
                <router-link :to="{name: 'requestPassword'}">忘記密碼?</router-link>
            </div>
            <button type="submit" :class="{'loading':loading}" :disabled="loading">
                <svg v-if="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span v-else>Login</span>
            </button>
        </form>
        <div v-if="errorMsg" class="errorMsg">
            <p> {{ errorMsg }}</p>
        </div>
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
        >.errorMsg{
            display: flex;
            align-items: center;
            justify-content: center;
            p{
                color:rgb(224, 0, 0);
                font-size: 14px;
            }
        }
        >h1{
            font-weight: 900;
            font-size: 30px;
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
                input[type='email'], input[type='password']{
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
                &.remember{
                    margin-right: auto;
                    color:#ccc;
                    input[type='checkbox']{
                        margin-left: auto;
                    }
                }
                
            }
            >.commit-text{
                color:#ccc;
                font-size: 14px;
                display: flex;
                width: 100%;
                justify-content: space-between;
                margin-left: auto;
                transition: .3s;
                padding: 0 10px;
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
                display: flex;
                justify-content: center;
                align-items: center;
                &:hover{
                    background-color: rgba(28, 132, 238,1);
                }
                &.loading{
                    cursor: not-allowed;
                    &:hover{
                        background-color: rgba(28, 132, 238,.85);
                    }
                }
            }
            
        }
    }
}
</style>