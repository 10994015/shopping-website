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
                        'price' => $product->price,
                        'quantity' => $cartItems[$product->id]['quantity'],
                        'total'=>number_format($product->price * $cartItems[$product->id]['quantity'], 2, '.', ''),
                    ])
                )
            }},
            get cartTotal(){
                return this.cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0).toFixed(2)
            }
        }">
            <div class="products">
                <table class="table-auto">
                    <thead>
                      <tr>
                        <th>商品名稱</th>
                        <th>價錢</th>
                        <th>數量</th>
                        <th>小計</th>
                      </tr>
                    </thead>
                    <tbody>
                        <template x-for="product of cartItems" :key="product.id">
                            <tr>
                                <td class="flex items-center">
                                    <img :src="product.image" :alt="product.title">
                                    <p x-text="product.title"></p>
                                </td>
                                <td>
                                    <p x-text=`$${product.price}`></p>
                                </td>
                                <td class=" ">
                                    <div class="input-number">
                                        <button class="decrement cart-decrement" onclick="cartStepper(this)">-</button>
                                        <input type="number" :value="product.quantity" id="cart-number" min="1" max="100" step="1" />
                                        <button class="increment cart-increment" onclick="cartStepper(this)">+</button>
                                    </div>
                                </td>
                                <td>
                                    <p x-text=`$${product.total}`></p>
                                </td>
                            </tr>
                        </template> 
                        
                    </tbody>
                  </table>
                
            </div>
            <div class="details">
                <div class="details-title">
                    <h5>總計</h5>
                </div>
                <div class="subtotal">
                    <div>
                        <p>小計</p>
                        <p x-text=`$${cartTotal}`></p>
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
                    <button>結帳</button>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script>
    document.querySelector('header').classList.add('isActive');
</script>
@endpush
</x-app-layout>