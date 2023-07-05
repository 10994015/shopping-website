<x-app-layout>
    <div class="shop-cart-component">
        <h3>購物車</h3>
        <div class="cart" x-data="{
            cartItems:{{
                json_encode(
                    $products->map(fn($product) => [
                        'id' => $product->id,
                        'slug' => $product->slug,
                        'image' => $product->image,
                        'title' => $product->title,
                        'price' => ($product->sale_price) ? $product->sale_price : $product->price,
                        'quantity' => $cartItems[$product->id]['quantity'],
                        'total'=>($product->sale_price) ? $product->sale_price * $cartItems[$product->id]['quantity'] : $product->price * $cartItems[$product->id]['quantity'],
                    ])
                )
            }},
            get products(){
                return this.cartItems.reduce((result, currentValue) => {
                    result[currentValue['id']] = currentValue;
                    return result;
                  }, {});
            },
            'freight':0,
            get cartSubTotal(){
                return this.cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0)
            },
            get cartTotal(){
                return (this.cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0) + this.freight)
            },
            removeChange:function(ids){
                ids = Array.from(ids)
                this.cartItems = this.cartItems.filter(p=> ids.includes(Number(p.id)) )
                
            },
            updateChange(ev){
                console.log(ev)
                this.products[ev.key].quantity = ev.quantity;
                if(ev.quantity <= 0){
                    axios.post(`/cart/remove/${this.products[ev.key].slug}`).then(res=>{
                        console.log(res.data.ids)
                        this.$dispatch('remove-change', res.data.ids)
                        this.$dispatch('cart-change', res.data)
                    })

                    return;
                }
                this.products[ev.key].total = (this.products[ev.key].price * this.products[ev.key].quantity).toFixed(2) 
                axios.post(`/cart/update-quantity/${this.products[ev.key].slug}`, {quantity:this.products[ev.key].quantity}).then(res=>{
                    this.$dispatch('cart-change', res.data)
                })
            }
        }" x-on:remove-change.window="removeChange($event.detail)" x-on:update-change.window="updateChange($event.detail)">
            <div class="products" x-show="cartItems.length">
                <table class="table-auto">
                    <thead>
                      <tr>
                        <th>商品名稱</th>
                        <th>價錢</th>
                        <th>數量</th>
                        <th>小計</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                        <template x-for="(product,key) of products" :key="product.id" x-data="{
                            removeCartItem:function(slug){
                                axios.post(`/cart/remove/${slug}`).then(res=>{
                                    this.$dispatch('remove-change', {cartItems:res.data.ids})
                                    this.$dispatch('cart-change', {count: res.data})
                                })
                            },
                            updateCartItemCount:function(quantity, key){
                                this.$dispatch('update-change', {quantity:quantity, key:key}) 
                            },
                            increment:function(product, key){
                                let newQuantity = Number(product.quantity) + 1
                                this.$dispatch('update-change', {quantity:newQuantity, key:key})
                            },
                            decrement:function(product, key){
                                let newQuantity = Number(product.quantity) -1 
                                this.$dispatch('update-change', {quantity:newQuantity, key:key})
                            },
                            
                        }">
                            <tr>
                                <td class="flex items-center">
                                    <img :src="product.image" :alt="product.title">
                                    <p x-text="product.title"></p>
                                </td>
                                <td>
                                    <p x-text=`$${parseInt(product.price)}`></p>
                                </td>
                                <td class=" ">
                                    <div class="input-number">
                                        <button class="decrement cart-decrement" x-on:click="decrement(product, key)">-</button>
                                        <input type="number" x-model="product.quantity" id="cart-number" min="1" max="100" step="1" x-on:change="updateCartItemCount(product.quantity, key)" />
                                        <button class="increment cart-increment" x-on:click="increment(product, key)">+</button>
                                    </div>
                                </td>
                                <td>
                                    <p x-text=`$${parseInt(product.total)}`></p>
                                </td>
                                <td><i class="fa-regular fa-circle-xmark" x-on:click="removeCartItem(`${product.slug}`)"></i></td>
                            </tr>
                        </template> 
                        
                    </tbody>
                  </table>
                
            </div>
            <div class="details" x-show="cartItems.length">
                <div class="details-title">
                    <h5>總計</h5>
                </div>
                <div class="subtotal">
                    <div>
                        <p>小計</p>
                        <p x-text=`$${parseInt(cartSubTotal)}`></p>
                    </div>
                    <div>
                        <p>運費</p>
                        <p x-text=`$${freight}`></p>
                    </div>
                    <div>
                        <p>總計</p>
                        <p x-text=`$${cartTotal}`></p>
                    </div>
                </div>
                <div class="coupon">
                    <input type="text" placeholder="請輸入折扣碼..." />
                    <button>套用</button>
                </div>
                <div class="checkout">
                    <form action="{{route('checkout.checkout')}}" method="post" x-data="{
                        loading:false,
                    }">
                        @csrf
                        <button @click="loading = true" >
                            <span x-show="!loading">結帳</span>
                            <svg x-show="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            <div x-show="!cartItems.length" class="empiyCart">
                <div>您的購物車目前是空的。</div>
                <p>Your cart is currently empty</p>
                <a href="/store">回商品列表</a>
            </div>
        </div>
    </div>
@push('scripts')
<script>
    document.querySelector('header').classList.add('isActive');
</script>
@endpush
</x-app-layout>