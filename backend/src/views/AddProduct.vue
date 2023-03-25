<script setup>
import { ref, onMounted, watch } from 'vue';
import store from '../store';
import {useRouter, useRoute} from "vue-router";
const route = useRoute();

const router = useRouter();

const DEFAULT_PRODUCT = {
    id: "",
    title:"",
    category_id :"",
    image:"",
    description:"",
    short_description:"",
    manufacturer_name:"",
    price:0,
    sale_price:null,
    hidden:false,
    featured:false,
}
const image_url = ref('');
const categories = ref([]);
const categoriesLock = ref({});
const lockEditFn = (id, bool)=>{
    categoriesLock.value[id].isLock = bool
}

const randerLoading = ref(false);

const categoryValue = ref('');
const categoryLoading = ref(false);
const categorySuccess = ref(false);
const openCategoryModel = ref(false);
const categoryModelFn = (bool)=>{
    openCategoryModel.value = bool;
    categorySuccess.value = false;
    categoryLoading.value = false;
    categoryValue.value = '';
}
const openEditCategoryModel = ref(false);
const openEditCategoryLoading = ref(false)
const categoryEditModelFn = (bool)=>{
    openEditCategoryModel.value = bool;
}
const loading = ref(false);
const previewLoading = ref(false);
const previewImg = ref(null);
const isPreview = ref(false);
const errorMsg = ref(null);
const successMsg = ref(null);
const product = ref({...DEFAULT_PRODUCT})
const isCreate = ref(false);
const getCategoriesFn = ()=>{
    openEditCategoryLoading.value = true;
    store.dispatch('getCategories').then(()=>{
        categories.value = store.state.categories.data;
        categories.value.forEach(item=>{
            categoriesLock.value[`${item.id}`]= {isLock:true, name:item.name, id:item.id, isLoading:false, success:false}
        })
        openEditCategoryLoading.value = false
    })
}
onMounted(() => {
    const productId = route.params.id;
    getCategoriesFn();
    if(productId === 'create') {
        randerLoading.value = true;
        product.id = productId;
        isCreate.value = true;
        return;
    };
    store.dispatch('isExistProduct', productId).then(res=>{
        if(res.data){
            store.dispatch('getProduct', productId).then(res=>{
                product.value = res.data;
                console.log(product.value);
                image_url.value = res.data.image_url;
                isPreview.value = true
                randerLoading.value = true;

                product.value.title = (product.value.title == 'null') ? "" :product.value.title;
                product.value.description = (product.value.description == 'null') ? "" :product.value.description;
                product.value.short_description = (product.value.short_description == 'null') ? "" :product.value.short_description;
                
            }).then(()=>{
                if(image_url.value != ""){
                    previewImg.value.src = image_url.value;
                }
            });
            
        }else{
            router.push({path:'/notfound'})
        }
    }).catch(err=>{
        console.error(err);
    })
})
const createCategoryFn= ()=>{
    categoryLoading.value = true
    store.dispatch('createCategory', categoryValue.value).then(()=>{
        categoryLoading.value = false;
        categorySuccess.value = true;
        getCategoriesFn();
        setTimeout(()=>{
            categorySuccess.value = false;
            categoryValue.value = ''
        },1000)
    }).catch(error=>{
        categoryLoading.value = false;
        categorySuccess.value = false;
        alert('分類已存在或不得為空！')
    });
}
const deleteCategory = (category)=>{
    if(confirm('確定要刪除此分類？')){
        openEditCategoryLoading.value = true
        store.dispatch('deleteCategory', category.id).then(()=>{
            categoriesLock.value = {}
            getCategoriesFn();
            alert('刪除成功！');
            openEditCategoryLoading.value = false;
        }).catch(err=>{
            console.error(err);
            openEditCategoryLoading.value = false;
        })
    }
}
const editCategoryFn = (id)=>{
    categoriesLock.value[id].isLoading = true
    store.dispatch('editCategory', {id: id, name: categoriesLock.value[id].name}).then(()=>{
        categoriesLock.value[id].isLoading = false
        categoriesLock.value[id].success = true
        getCategoriesFn();
        setTimeout(()=>{
            categoriesLock.value[id].success = false
            categoriesLock.value[id].isLock = true;
        },1000)
    }).catch(error=>{
        categoriesLock.value[id].isLoading = false
        categoriesLock.value[id].success = false
        categoriesLock.value[id].isLock = true;
        alert('分類已存在或不得為空！')
    });
}
const previewImage = (ev)=>{
    previewLoading.value = true;
    if(ev.target.files && ev.target.files[0]){
        product.value.image = ev.target.files[0];
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
    console.log(product.value);
    console.log(product.value.featured);
    console.log(product.value.hidden);
    loading.value = true;
    if(isCreate.value){
        store.dispatch('createProduct', product.value).then(res=>{
        if(res.status === 200 || res.status === 201){
            successMsg.value = "上傳成功！";
            errorMsg.value = null;
        }
        loading.value = false;
        }).catch(err=>{
            loading.value = false;
            errorMsg.value = err.response.data.errors;
        })
    }else{
        store.dispatch('updateProduct', product.value).then(res=>{
            if(res.status === 200 || res.status === 201){
                successMsg.value = "更新成功！";
                errorMsg.value = null;
            }
            loading.value = false;
        }).catch(err=>{
            loading.value = false;
            errorMsg.value = err.response.data.errors;
        })
    }
    
};
watch(()=>product.value, (val)=>{
    successMsg.value = null;
}, {deep:true});
</script>

<template>
<div class="addProduct">
    <div class="add-category-model" v-show="openCategoryModel">
        <div class="back"></div>
        <div class="model" v-show="openCategoryModel">
            <div class="model-header" ><h3>Create Category</h3><i class="fas fa-times" @click="categoryModelFn(false)"></i></div>
            <div class="model-body">
                <input type="text" placeholder="Category name..." v-model="categoryValue" />
                <button @click="createCategoryFn()" :class="{'disabled':(categoryLoading || categorySuccess)}" :disabled="categoryLoading || categorySuccess">
                <svg v-if="categoryLoading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <svg v-if="categorySuccess" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                <span v-if="!categoryLoading && !categorySuccess">Create Category</span>
                </button>
            </div>
        </div>
    </div>
    <div class="edit-category-model" v-show="openEditCategoryModel">
        <div class="back"></div>
        <div class="model" v-show="openEditCategoryModel">
            <div class="model-header" ><h3>Edit Category</h3><i class="fas fa-times" @click="categoryEditModelFn(false)"></i></div>
            <div class="model-body flex justify-center flex-col items-center">
                <svg v-if="openEditCategoryLoading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <label v-else v-for="category in categoriesLock" :key="category.id" class="mb-4 w-full">
                    <input type="text" placeholder="Category name..." v-model="category.name" :disabled="category.isLock" />
                    <button v-if="category.isLock" @click="lockEditFn(category.id, false)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-700">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </button>
                    <button v-if="category.isLock" @click="deleteCategory(category)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                    </button>
                    <button v-if="!category.isLock" @click="lockEditFn(category.id, true)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-700">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </button>
                    <button v-if="!category.isLock" @click="editCategoryFn(category.id)" :disabled="category.isLoading">
                        <svg v-if="category.isLoading" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg v-if="!category.isLoading && !category.success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-700">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />
                        </svg>
                        <svg v-if="category.success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </button>
                </label>
                
            </div>
        </div>
    </div>
    <h1>Add New Product</h1>
    <div class="card">
        <div class="card-title">
            <h2>Basic Information</h2>
            <p>Fill all information below</p>
            <span v-if="successMsg">{{ successMsg }}</span>
            <div class="categoryBtn">
                <button @click="categoryModelFn(true)" class="add-product-category"><i class="fa-solid fa-plus"></i>Add product category</button>
                <button @click="categoryEditModelFn(true)" class="add-product-category"><i class="fa-solid fa-plus"></i>Edit product category</button>
            </div>
        </div>
        <form v-if="randerLoading" action="" @submit.prevent="onSubmit()">
            <div class="form-group">
                <label for="">產品名稱</label>
                <input type="text" v-model="product.title" />
            </div>
            <div class="form-group">
                <label for="">產品分類</label>
                <select v-model="product.category_id">
                    <option value="">請選擇分類</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">產品簡短描述</label>
                <input type="text" v-model="product.short_description" />
            </div>
            <div class="form-group">
                <label for="">生產商名稱</label>
                <input type="text" v-model="product.manufacturer_name" />
            </div>
            <div class="form-group">
                <label for="">產品價格</label>
                <div class="input-group">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <input type="number" v-model="product.price" />
                </div>
            </div>
            <div class="form-group">
                <label for="">產品售價</label>
                <div class="input-group">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <input type="number" v-model="product.sale_price" />
                </div>
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
                        <img src=""  ref="previewImg" id="previewImg" />
                    </div>
                </label>
                <input type="file" id="imagefile" hidden @change="previewImage($event)"  />
            </div>
            <div class="chkbox-group">
                <div class="form-group ">
                    <label for="">設為精選</label>
                    <input type="checkbox" v-model="product.featured"  />
                </div>
                <div class="form-group ml-10">
                    <label for="">隱藏產品</label>
                    <input type="checkbox" v-model="product.hidden" />
                </div>
            </div>
            <div class="form-group"></div>
            <div class="form-group btn-group mt-10">
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
        <div v-else class="flex items-center justify-center py-10">
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
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
    .add-category-model, .edit-category-model{
        position: fixed;
        top: 0;
        left:0;
        width: 100%;
        height: 100vh;
        z-index: 50;
        >.back{
            position: absolute;
            top: 0;
            left:0;
            width: 100%;
            height: 100%;
            background-color: rgba($color: #000000, $alpha: .5);
        }
        >.model{
            position: absolute;
            top: 50%;
            left:50%;
            transform: translate(-50%, -50%);
            width: 98%;
            max-width: 500px;
            background-color: #242A30;
            border:1px #2d343c solid;
            border-radius: 0.25rem;
            animation: openCategoryModel .2s linear;
            @keyframes openCategoryModel {
                0%{
                    transform: translate(-50%, -45%);
                    opacity: .25;
                }
            }
            >.model-header{
                border-bottom: 1px #30373f solid;
                padding: 15px 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                >i{
                    cursor: pointer;
                }
            }
            >.model-body{
                padding: 30px 20px;
                >input{
                    border:none;
                    outline: none;
                    border-radius: 5px;
                    padding: 0 12px;
                    background-color: #282F36;
                    color:#adb5bd;
                    border:1px #30373f solid;
                    height: 40px;
                    font-size: 14px;
                    width: 100%;
                }
                >button{
                    background-color: #1C84EE;
                    color:#fff;
                    border-radius: 8px;
                    padding: 10px 23px;
                    margin-left: auto;
                    transition: .3s;
                    font-size: 14px;
                    width: 100%;
                    margin-top: 25px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    &.disabled{
                        background-color: #1870ca;
                        border-color: #1870ca;
                        cursor: not-allowed;
                    }
                    &:hover{
                        background-color: #1870ca;
                        border-color: #1870ca;
                    }
                }
                >label{
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    >input{
                        border:none;
                        outline: none;
                        border-radius: 5px;
                        padding: 0 12px;
                        background-color: #282F36;
                        color:#adb5bd;
                        border:1px #30373f solid;
                        height: 40px;
                        font-size: 14px;
                        width: 84%;
                    }
                }
            }
        }
    }
    >h1{
        font-weight: 900;
        color:#fff;
    }
    >.card{
        background-color: #242A30;
        border-radius:12px;
        padding: 1.5rem 4rem 1.5rem 2.5rem;
        margin-top: 25px;
        >.card-title{
            border-bottom: 1px #2d343c solid;
            padding-bottom: 25px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
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
                padding:10px 20px ;
                font-size: 13px;
            }
            >.categoryBtn{
                margin-left: auto;
                button{
                    background-color: #1C84EE;
                    color:#fff;
                    border-radius: 25px;
                    padding: 10px 23px;
                    transition: .3s;
                    font-size: 13px;
                    margin-left: 16px;
                    &:hover{
                        background-color: #1870ca;
                        border-color: #1870ca;
                    }
                    >i{
                        margin-right: 3px;
                    }
                }
            }
           
        }
        >form{
            width: 100%;
            display: grid;
            grid-template-columns: repeat(2, 50%);
            grid-column-gap: 30px;
            .chkbox-group{
                display: flex;
                justify-content: flex-start;
                align-items: center;
            }
            .form-group{
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
                input[type='text'], input[type='number'], select{
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
                >.input-group{
                    display: flex;
                    align-items: center;
                    width: 100%;
                    .icon{
                        background-color: hsla(0,0%,100%,.1);
                        width: 36px;
                        height: 36px;
                        border:none;
                        padding: 7.5px 0 7.5px 9px;
                        border-radius: 5px 0 0 5px;
                        margin-right: 0;
                    }
                    
                    input{
                        margin-left: 0;
                        width: calc(100% - 36px);
                    }
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

input[type="checkbox"] ,input[type="radio"]{
    position: relative;
    width:50px;
    height: 25px;
    outline: none;
    background:linear-gradient(to right,#bbb ,#999);
    -webkit-appearance: none;
    cursor: pointer;
    border-radius: 20px;
    &::before{
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width:25px;
        height: 25px;
        background: #fff;
        border-radius: 50%;
        transform: scale(0.98,0.96);
        transition: .5s;
    }
    &:checked{
        background: linear-gradient(to right,#1C84EE ,#185CC9);
        &::before{
            left:25px;
        }

    }
    &::after{
        content:'';
    }
    
}
</style>