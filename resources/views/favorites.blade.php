<x-app-layout>
    <div class="favorites-componet" x-data="{
        products: {{ json_encode($products)}},
        init(){
            console.log(this.products)
        }
    }">
        <div class="products-list">
            <template x-for="product, ids in products" :key="product.product.id">
            <div class="item" 
                x-data="{
                    isLoading:false,
                    productItem:product.product,
                    addToCart:function(slug){
                        if(!this.isLoading){
                            this.isLoading = true;
                            setTimeout(()=>{
                                axios.post('/cart/add/' + slug ,{product:this.productItem, quantity:1}).then(res=>{
                                    this.$dispatch('cart-change', res.data)
                                    this.$dispatch('shop-add-change', res.data)
                                });
                                this.isLoading = false;
                            },500)
                        }
                    },
                    
                }"
            >
                <div class="add-cart" x-on:click="addToCart(product.product.slug)">
                    <i  x-show="!isLoading" class="fa-solid fa-bag-shopping"></i>
                    <div x-show="isLoading" class="loading"></div>
                    <input type="hidden" :value="product.id" class="productId" />
                </div>
                <template x-if="product.sale_price">
                    <div class="sale-tag">Sale!</div>
                </template>
                <div class="toolbox">加入購物車</div>
                <img :src="product.product.image" :alt="product.product.title" x-on:click="window.location.href='/product-detail/' + product.product.slug "
                 />
                <small></small>
                <h3 x-text="product.product.title"></h3>
                <span><i class="fa-solid fa-star"></i>4.7</span>
                <template x-if="product.product.sale_price">
                    <div class="price-row"><span class="price" x-text="'$' + product.product.price"></span><span class="sale-price" x-text="'$'+product.product.sale_price"></span></div>
                </template>
                <template x-if="!product.sale_price">
                    <div class="price-row"><span class="sale-price" x-text="'$'+product.product.price"></span></div>
                </template>
            </div>
            </template>
        </div>
    </div>

</x-app-layout>