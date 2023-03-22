<x-app-layout>
    <div class="shop-cart-component">
        <h3>購物車</h3>
        <div class="cart">
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
                        @for($i=0;$i<5;$i++)
                        <tr>
                            <td class="flex items-center">
                                <img src="/images/plant3-free-img.jpg" alt="">
                                <p>黑色扶手椅</p>
                            </td>
                            <td>
                                <p>$1000</p>
                            </td>
                            <td class=" ">
                                <div class="input-number">
                                    <button class="decrement cart-decrement" onclick="cartStepper(this)">-</button>
                                    <input type="number" value="1" id="cart-number" min="1" max="100" step="1" />
                                    <button class="increment cart-increment" onclick="cartStepper(this)">+</button>
                                </div>
                            </td>
                            <td>
                                <p>$1000</p>
                            </td>
                        </tr>
                        @endfor
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
                        <p>$1000</p>
                    </div>
                    <div>
                        <p>總計</p>
                        <p>$1000</p>
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