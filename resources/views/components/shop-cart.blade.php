<div class="shop-cart" id="shop-cart-sildebar">
    <div class="back"></div>
    <div class="cart">
        <div class="cart-title">
            <h4>購物車</h4>
            <i class="fas fa-times" id="close-cart"></i>
        </div>
        <div class="product-list">
            <div class="item">
                <div class="product">
                    <img src="/images/plant3-free-img.jpg" alt="">
                    <div>
                        <h4>黑色扶手椅</h4>
                        <div class="input-number">
                            <button class="decrement cart-decrement" onclick="cartStepper(this)">-</button>
                            <input type="number" value="1" id="cart-number" min="1" max="100" step="1" />
                            <button class="increment cart-increment" onclick="cartStepper(this)">+</button>
                        </div>
                    </div>
                </div>
                <div class="detail">
                    <div class="detail-info">
                        <i class="fa-regular fa-circle-xmark"></i>
                        <p>$ 128</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="checkout-btn">
            <div class="subtotal">
                <p>小計:</p>
                <p>$ 193</p>
            </div>
            <a href="/cart" class="view-cart">查看購物車</a>
            <a href="##" class="to-checkout">前往結帳</a>
        </div>
    </div>
</div>