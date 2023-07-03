<div class="shop-cart" id="shop-cart-sildebar" 
    x-data="{
        cartItems: {{
            \App\Http\Helpers\Cart::getProducts(json_encode(\App\Http\Helpers\Cart::getCartItems()))
        }} ,
        get cartItemsSubTotal(){
            return Object.values(this.cartItems).reduce((accum, next) => accum + next.price * next.quantity, 0)
        },
        get shopProducts(){
            return Object.values(this.cartItems).reduce((result, currentValue) => {
                result[currentValue['id']] = currentValue;
                return result;
              }, {});
        },
        init(){
        },
        shopRemoveChange(ids){
            this.cartItems = Object.values(this.cartItems).filter(p=> ids.includes(Number(p.id)) )
        },
        shopCartItemChange(ev){
            const cartItems = ev.cartItems
            axios.post(`/cart/get-products`, {cartItems: cartItems}).then(res=>{
                this.cartItems = res.data
            })
        },
        shopUpdateChange:function(ev){
            console.log(ev)
            this.cartItems[ev.key].quantity = ev.quantity
            if(this.cartItems[ev.key].quantity <= 0){
                axios.post(`/cart/remove/${this.cartItems[ev.key].slug}`).then(res=>{
                    this.$dispatch('shop-remove-change', res.data.ids)
                    this.$dispatch('cart-change', res.data)
                })
                return;
            }
            axios.post(`/cart/update-quantity/${this.shopProducts[ev.key]['slug']}`, {quantity:this.cartItems[ev.key].quantity}).then(res=>{
                this.$dispatch('cart-change', res.data)
            })
        }
    }"
    x-on:shop-update-change.window="shopUpdateChange($event.detail)"
    x-on:shop-remove-change.window="shopRemoveChange($event.detail)"
    x-on:shop-add-change.window="shopCartItemChange($event.detail)"
    x-on:shop-remove-change.window="shopCartItemChange($event.detail)"
    >
    <div class="back"></div>
    <div class="cart">
        <div class="cart-title">
            <h4>購物車</h4>
            <i class="fas fa-times" id="close-cart"></i>
        </div>
        <div class="product-list">
            <template x-if="Object.values(cartItems).length">
                <template x-for="(product, key) of cartItems" :key="product.id" x-data="{
                    increment:function(product, key){
                        let newQuantity = Number(product.quantity) + 1
                        this.$dispatch('shop-update-change', {quantity:newQuantity, key:key})
                    },
                    decrement:function(product, key){
                        let newQuantity = Number(product.quantity) -1 
                        this.$dispatch('shop-update-change', {quantity:newQuantity, key:key})
                    },
                    removeCartItem:function(slug){
                        axios.post(`/cart/remove/${slug}`).then(res=>{
                            this.$dispatch('shop-remove-change', res.data.ids)
                            this.$dispatch('cart-change', res.data)
                        })
                    },
                    changeCount:function(ev, key, slug){
                        let newQuantity = Number(ev.value)
                        if(newQuantity > 0){
                            this.$dispatch('shop-update-change', {quantity:newQuantity, key:key})
                        }else{
                            axios.post(`/cart/remove/${slug}`).then(res=>{
                                this.$dispatch('shop-remove-change', res.data.ids)
                                this.$dispatch('cart-change', res.data)
                            })
                        }
                    },
                }">
                    <div class="item" >
                        <div class="product">
                            <img :src="product.image" :alt="product.title">
                            <div>
                                <h4 x-text="product.title"></h4>
                                <div class="input-number">
                                    <button class="decrement cart-decrement" x-on:click="decrement(product, key)">-</button>
                                    <input type="number" @change="changeCount($event.target, key, product.slug)" x-model="product.quantity" id="cart-number" min="1" max="100" step="1" />
                                    <button class="increment cart-increment" x-on:click="increment(product, key)">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="detail">
                            <div class="detail-info">
                                <i class="fa-regular fa-circle-xmark" x-on:click="removeCartItem(`${product.slug}`)"></i>
                                <p x-text=`$${(product.sale_price)?product.sale_price*product.quantity:product.price*product.quantity}`></p>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
            <template x-if="!Object.values(cartItems).length">
                <div>
                    <p class="text-gray-600 text-sm text-center">您的購物車目前無任何商品。</p>
                </div>
            </template>
        </div>
        <div class="checkout-btn">
            <div class="subtotal">
                <p>小計:</p>
                <p x-text=`$${cartItemsSubTotal}`></p>
            </div>
            <a href="/cart" class="view-cart">查看購物車</a>
            <a href="##" class="to-checkout">前往結帳</a>
        </div>
    </div>
</div>