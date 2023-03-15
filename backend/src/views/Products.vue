<script setup>
import { ref, computed, onMounted } from 'vue';
import store from '../store';
import {PRODUCTS_PER_PAGE} from "../constants";
const perPage = ref(PRODUCTS_PER_PAGE);
const search = ref('');
const products = computed(()=> store.state.products);

onMounted(() => {
    getProducts();
})
const getProducts = ()=>{
    store.dispatch('getProducts');
};
</script>

<template>
<div class="products">
    <h1>Products</h1>
    <div class="card">
        <div class="card-header">
            <div class="left">
                <div class="form-group">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" placeholder="Search..." v-model="search" />
                </div>
                <div class="form-group">
                    <select v-model="perPage">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
            <div class="right">
                <div class="form-group">
                    <router-link class="btn" :to="{name:'app.products'}">+ Add New Product</router-link>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-auto">
                <thead>
                    <tr>
                        <th><input type="checkbox"></th>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Last Updated At</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product of products.data.data" :key="product.id">
                        <td><input type="checkbox" :name="product.slug"></td>
                        <td>{{product.id}}</td>
                        <td><img :src="product.image" /></td>
                        <td>{{product.title}}</td>
                        <td>{{product.price}}</td>
                        <td>{{ product.updated_at }}</td>
                        <td>Status</td>
                    </tr>
                </tbody>
                </table>
        </div>
    </div>
</div>

</template>

<style lang="scss" scoped>
.products{
    display: flex;
    flex-direction: column;
    h1{
        font-weight: 900;
        color:#fff;
    }
    >.card{
        background-color: #242A30;
        border-radius:12px;
        padding: 1.5rem 2.5rem;
        margin-top: 25px;
        >.card-header{
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
            .left, .right{
                display: flex;
                justify-content: flex-start;
                align-items: center;
                .form-group{
                    display: flex;
                    align-items: center;
                    .icon{
                        background-color: #282F36;
                        width: 40px;
                        height: 40px;
                        border: 1px solid #30373f;
                        border-right: none;
                        padding: 11px 0px 12px 19px;
                        border-radius: 30px 0 0 30px;
                        margin-right: 0;
                    }
                    input[type='text']{
                        background-color: #282F36;
                        border: 1px solid #30373f;
                        width:190px;
                        height: 40px;
                        border-radius: 30px;
                        border-top-left-radius: 0;
                        border-bottom-left-radius: 0;
                        border-left: 0;
                        outline: none;
                        padding: 15px 18px 15px 16px;
                        font-size: 14px;
                    }
                    .btn{
                        color:#f6f6f6;
                        cursor: pointer;
                        background-color: #34c38f;
                        border-radius: 35px;
                        display: block;
                        padding:10px 20px;
                        text-align: center;
                        font-weight: 900;
                        font-size: 14px;
                        transition: .3s;
                        &:hover{
                            background-color: #2ca67a;
                        }
                    }
                    select{
                        color:#adb5bd;
                        background-color: #282f36;
                        border: 1px solid #30373f;
                        border-radius: 0.25rem;
                        width: 100px;
                        height: 40px;
                        padding: 0 7px;
                        outline: none;
                        margin-left:15px;
                    }
                }
            }
            
        }
        >.table-responsive{
            margin-top: 20px;
            > table{
                margin:0 auto;
                width: 100%;
                font-size: 14px;
                >thead{
                    background-color: #363A38;
                    >tr{
                        >th{
                            text-align: left;
                            padding:  15px;
                        }
                    }
                }
                >tbody{
                    background-color: transparent;
                    >tr{
                        border-bottom: 1px #2f373f solid;
                        >td{
                            text-align: left;
                            padding:  15px;
                            img{
                                width:65px;
                                height: 40px;
                                object-fit: cover;
                            }
                        }
                    }
                }
            }
        }
    }
}
input[type="checkbox"] {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  border: 2px solid #aaa;
  border-radius: 5px;
  width: 16px;
  height: 16px;
  outline: none;
  cursor: pointer;
  position: relative; /* 讓勾選圖示相對定位 */
}

/* 未選中時的樣式 */
input[type="checkbox"]:not(:checked) {
  background-color: #fff;
}

/* 選中時的樣式 */
input[type="checkbox"]:checked {
  background-color: #2196f3;
}

/* 鼠標懸停時的樣式 */
input[type="checkbox"]:hover {
  border-color: #555;
}

/* 勾選圖示樣式 */
input[type="checkbox"]:checked::before {
  content: "";
  display: block;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%) rotate(45deg);
  width: 5px;
  height: 10px;
  border-bottom: 2px solid #fff;
  border-right: 2px solid #fff;
}
</style>