<script setup>
import { ref, computed, onMounted } from 'vue';
import store from '../store';
import {PRODUCTS_PER_PAGE} from "../constants";
const perPage = ref(PRODUCTS_PER_PAGE);
const search = ref('');
const sortField = ref('updated_at');
const sortDirection = ref('desc');
const products = computed(()=> store.state.products);
onMounted(() => {
    getProducts();
})
const getProducts = (url = null)=>{
    store.dispatch('getProducts', {url, sort_field:sortField.value, sort_direction: sortDirection.value, search: search.value, perPage:perPage.value});
};
const getForPage = (ev, link)=>{
    if(!link.url || link.active) return;

    getProducts(link.url);
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
            <table class="table table-auto w-full">
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
                <tbody v-if="products.loading" class="loadingTable">
                    <tr>
                        <td colspan="7" class="w-full" style="text-align:center">
                            <svg class="animate-spin h-5 w-5 text-white"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr v-for="product of products.data" :key="product.id">
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
        <div class="paging" v-if="products.total > products.limit">
            <div class="pageInfo">Showing from {{products.from}} to {{products.to}}</div>
            <div class="pageBtn">
                <nav>
                    <a href="#" v-for="(link, i) of products.links" :key="i" @click.prevent="getForPage($event, link)" :disabled="!link.url" :class="[{'active': link.active}, {'disabled':!link.url}]" v-html="link.label"></a>
                </nav>
            </div>
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
                    &.loadingTable{
                        td{
                            text-align: center;
                            padding: 30px 0;
                            >svg{
                                margin:0 auto;
                            }
                        }
                    }
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
        >.paging{
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            >.pageBtn{
                nav{
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    a{
                        color: #fff;
                        border-radius: 30px!important;
                        margin: 0 3px!important;
                        border: none;
                        width: 32px;
                        height: 32px;
                        padding: 0;
                        text-align: center;
                        line-height: 32px;
                        font-size: 12px;
                        transition: .3s;
                        &:hover{
                            color:#1c84ee;
                            background-color: #282f36;
                        }
                        &.active{
                            background-color: #1c84ee;
                            border-color: #1c84ee;
                            &:hover{
                                color:#fff;
                                background-color: #1c84ee;
                            }
                        }
                        &.disabled{
                            cursor: default;
                            color:#777;
                            &:hover{
                                color:#777;
                                background-color: #242A30;
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