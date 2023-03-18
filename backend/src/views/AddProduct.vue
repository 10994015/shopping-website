<script setup>
import { ref } from 'vue';
import store from '../store';
import {useRouter} from "vue-router";

const router = useRouter();

const loading = ref(false);
const previewLoading = ref(false);
const previewImg = ref(null);
const isPreview = ref(false);
const errorMsg = ref(null);
const successMsg = ref(null);
const product = ref({
    id: "",
    title:"",
    image:"",
    description:"",
    price:0
})
const cancel = ()=>{
}
const previewImage = (ev)=>{
    previewLoading.value = true;
    if(ev.target.files && ev.target.files[0]){
        product.value.image = ev.target.files[0];
        console.log(product.value.image);

        const reader = new FileReader();
        reader.onload = (e)=> {
            previewImg.value.src = e.target.result;
        };
        reader.readAsDataURL(ev.target.files[0]);
    }
    previewLoading.value = false;
    isPreview.value = true;
}
const onSubmit = ()=>{
    loading.value = true;
    store.dispatch('createProduct', product.value).then(res=>{
      if(res.status === 200 || res.status === 201){
        successMsg.value = "上傳成功！";
        errorMsg.value = null;
      }
      loading.value = false;
    }).catch(err=>{
        loading.value = false;
        console.error(err);
        errorMsg.value = err.response.data.errors;
    })
};

</script>

<template>
<div class="addProduct">
    <h1>Add New Product</h1>
    <div class="card">
        <div class="card-title">
            <h2>Basic Information</h2>
            <p>Fill all information below</p>
            <span v-if="successMsg">{{ successMsg }}</span>
        </div>
        <form action="" @submit.prevent="onSubmit()">
            <div class="form-group">
                <label for="">產品名稱</label>
                <input type="text" v-model="product.title" />
            </div>
            <div class="form-group">
                <label for="">產品價格</label>
                <input type="number" v-model="product.price" />
            </div>
            <div class="form-group">
                <label for="">產品描述</label>
                <textarea v-model="product.description" ></textarea>
            </div>
            <div class="form-group">
                <label for="">產品圖片</label>
                <label  for="imagefile" class="imagefileFor">
                    <svg v-if="previewLoading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <div v-if="!isPreview">
                        <svg  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mb-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                        <span>將文件拖放到此處或單擊以上傳。</span>
                    </div>
                    <div v-else class="isPreview">
                        <img src=""  ref="previewImg" />
                    </div>
                </label>
                <input type="file" id="imagefile" hidden @change="previewImage($event)"  />
            </div>
            <div class="form-group btn-group">
                <button type="submit" :class="{'loading':loading}">
                    <svg v-if="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span v-else>保存更改</span>
                </button>
                <button class="pre" type="button" @click="router.push({name:'app.products'})" >回列表</button>
            </div>
        </form>
        <div class="errorMsg" v-if="errorMsg">
            <span v-for="(error, i) in errorMsg" :key="i"> {{error[0]}}</span>
        </div>
    </div>
</div>
</template>

<style lang="scss" scoped>
.addProduct{
    display: flex;
    flex-direction: column;
    >h1{
        font-weight: 900;
        color:#fff;
    }
    >.card{
        background-color: #242A30;
        border-radius:12px;
        padding: 1.5rem 2.5rem;
        margin-top: 25px;
        >.card-title{
            border-bottom: 1px #2d343c solid;
            padding-bottom: 15px;
            h2{
                color:#fff;
                font-size: 17px;
                font-weight: 800;
            }
            p{
                margin-top: 5px;
                margin-bottom: 15px;
            }
            span{
                margin-top: 15px;
                background-color: rgb(0, 190, 48);
                color:#fff;
                border-radius: 3px;
                padding:6px 12px ;
                font-size: 13px;
            }
        }
        >form{
            width: 100%;
            display: grid;
            grid-template-columns: repeat(2, 50%);
            grid-column-gap: 30px;
            >.form-group{
                display: flex;
                flex-direction: column;
                padding: 12px 0;
                &.btn-group{
                    flex-direction: row;
                }
                >label{
                    margin-bottom: 10px;
                    font-weight: 900;
                    font-size: 13px;
                    color:#CED4DA;
                }
                >.imagefileFor{
                    width:100%;
                    height: 140px;
                    background-color: #282F36;
                    border:2px #30373f dashed;
                    cursor: pointer;
                    >div{
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        flex-direction: column;
                        font-size: 15px;
                        position: relative;
                        width: 100%;
                        height: 100%;
                        &.isPreview >svg{
                            position: absolute;
                            top: 8px;
                            right:15px;
                            color:#fff;
                            cursor: pointer;
                        }
                        >img{
                            position: absolute;
                            top: 50%;
                            left:50%;
                            transform: translate(-50%, -50%);
                            height: 100%;
                            object-fit: contain;
                        }
                    }
                    
                }
                >input[type='text'], input[type='number']{
                    border:none;
                    outline: none;
                    border-radius: 5px;
                    padding: 0 12px;
                    background-color: #282F36;
                    color:#adb5bd;
                    border:1px #30373f solid;
                    height: 36px;
                    font-size: 14px;
                }
                >textarea{
                    border:none;
                    outline: none;
                    border-radius: 5px;
                    padding:8px 12px;
                    background-color: #282F36;
                    color:#adb5bd;
                    border:1px #30373f solid;
                    height: 140px;
                    font-size: 14px;
                    resize: none;
                    resize: vertical;
                }
                >button, .pre{
                    color:#f6f6f6;
                    background-color: #1c84ee;
                    border-color: #1c84ee;
                    border-radius: 5px;
                    height: 38px;
                    font-size: 13px;
                    font-weight: 500;
                    width: auto;
                    transition: .3s;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    padding: 0 20px;
                    &:hover{
                        background-color: #1870ca;
                        border-color: #1870ca;
                    }
                    &.loading{
                        cursor: not-allowed;
                        background-color: #1870ca;
                        border-color: #1870ca;
                    }
                    >svg{
                        text-align: center;
                    }
                }
                .pre{
                    background-color: #74788D;
                    color:#F6F6F6;
                    margin-left: 10px;
                    &:hover{
                        background-color: #636678;
                        border-color: #5d6071;
                    }
                }
            }
        }
        >.errorMsg{
            display: flex;
            flex-direction: column;
            >span{
                color:rgb(180, 0, 0);
                margin-top: 4px;
            }
        }
    }
}

input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type="number"] {
  -moz-appearance: textfield; /* Firefox */
}
</style>