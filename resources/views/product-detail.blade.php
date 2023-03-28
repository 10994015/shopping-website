<x-app-layout>
    <div class="product-detail" >
        <div class="product">
            <div class="product-image">
                <div class="imgBox">
                    <div class="magnifying">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <img src="{{$product->image}}" alt="{{$product->title}}" />
                </div>
            </div>
            <div class="product-intro" x-data="{
                productItem:{{json_encode($product)}},
                quantity:1,
                decrementFn(){
                    if(this.quantity>1) {
                        this.quantity--
                    }
                },
                incrementFn(){
                    if(this.quantity<100) {
                        this.quantity++
                    }
                },
                changeQuantity(){
                    this.quantity = Number($el.querySelector('#cart-number').value) 
                },
                addToCart:function(slug){
                    if(!this.isLoading){
                        this.isLoading = true;
                        axios.post('/cart/add/slug',{product:this.productItem, quantity:this.quantity}).then(res=>{
                            this.$dispatch('cart-change', {count: res.data})
                            this.$dispatch('shop-add-change', {count: res.data})
                        });
                        setTimeout(()=>{
                            this.isLoading = false;
                        },1000)
                    }
                }
            }">
                <a href="/store/{{$product->category->slug}}" class="category-name">{{$product->category->name}}</a>
                <h1 class="product-title">{{$product->title}}</h1>
                <div class="price-list">
                    @if($product->sale_price)
                    <p class="ori-price">${{$product->price}}</p>
                    <p class="sale-price">${{$product->sale_price}} </p>
                    @else
                    <p class="sale-price">${{$product->price}} </p>
                    @endif
                    <span>+免運費</span>
                </div>
                <p class="short-description">{{$product->short_description}}</p>
                <div class="add-cart">
                    <div class="input-number">
                        <button class="decrement" id="decrement" x-on:click="decrementFn">-</button>
                        <input type="number"  :value="quantity" id="cart-number" min="1" max="100" step="1" x-on:change="changeQuantity" />
                        <button class="increment" id="increment" x-on:click="incrementFn">+</button>
                    </div>
                    <button type="button" class="addtocart" x-on:click="addToCart('{{$product->slug}}')">加入購物車</button>
                </div>
                <div class="category">Category: <a href="/store/{{$product->category->slug}}">{{$product->category->name}}</a></div>
            </div>
        </div>
        <div class="detailed" x-data="{
            isShowDescription:true,
            openDescription(){
                this.isShowDescription = true
            },
            openComment(){
                this.isShowDescription = false
            },
        }" >
            <div class="detailed-btn">
                <button x-bind:class="{'active':isShowDescription}" id="desctiption-btn" @click="openDescription">商品描述</button>
                <button x-bind:class="{'active':!isShowDescription}" id="comment-btn" @click="openComment">商品評論 (0)</button>
            </div>
            <div class="description" x-show="isShowDescription">
                <p>
                    @php echo nl2br($product->description) @endphp
                </p>
            </div>
            <div class="comment" x-show="!isShowDescription">
                <p class="null-comment">還沒有任何評論。</p>
                <form action="">
                    <h3>成為第一個評論"{{$product->title}}"的人</h3>
                    <label>您的電子郵件地址不會被公開。 必填字段已標記*</label>
                    <label class="flex items-center">您的評價* 
                        <div class="rating">
                            <input type="radio" id="star5" name="rating" value="5" />
                            <label for="star5">&#9733;</label>
                            <input type="radio" id="star4" name="rating" value="4" />
                            <label for="star4">&#9733;</label>
                            <input type="radio" id="star3" name="rating" value="3" />
                            <label for="star3">&#9733;</label>
                            <input type="radio" id="star2" name="rating" value="2" />
                            <label for="star2">&#9733;</label>
                            <input type="radio" id="star1" name="rating" value="1" />
                            <label for="star1">&#9733;</label>
                          </div>
                    </label>
                    <label for="">
                        <p>您的評論*</p>
                        <textarea></textarea>
                    </label>
                    <label for="">
                        <button>提交</button>
                    </label>
                </form>
            </div>
        </div>
        <div class="belike">
            <h3>您可能會喜歡</h3>
            <div class="products-list">
                @foreach($products as $product)
                <div class="item"
                    x-data="{
                        isLoading:false,
                        productItem:{{json_encode($product)}},
                        addToCart:function(slug){
                            if(!this.isLoading){
                                this.isLoading = true;
                                setTimeout(()=>{
                                    axios.post('/cart/add/slug',{product:this.productItem, quantity:1}).then(res=>{
                                        this.$dispatch('cart-change', {count: res.data})
                                        this.$dispatch('shop-add-change', {count: res.data})
                                    });
                                    this.isLoading = false;
                                },500)
                            }
                        }
                    }"
                >
                    <div :class="['add-cart']" x-on:click="addToCart('{{$product->slug}}')" >
                        <i x-show="!isLoading" class="fa-solid fa-bag-shopping"></i>
                        <div x-show="isLoading" class="loading"></div>
                    </div>
                    @if($product->sale_price)
                    <div class="sale-tag">Sale!</div>
                    @endif
                    <div class="toolbox">加入購物車</div>
                    <img src="{{$product->image}}" alt="{{$product->title}}" />
                    <small>{{$product->category->name}}</small>
                    <h3>{{$product->title}}</h3>
                    <span><i class="fa-solid fa-star"></i>4.7</span>
                    @if($product->sale_price)
                    <div class="price-row"><span class="price">${{$product->price}}</span><span class="sale-price">${{$product->sale_price}}</span></div>
                    @else
                    <div class="price-row"><span class="sale-price">${{$product->price}}</span></div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
       
    </div>

@push('scripts')
<script>
    document.querySelector('header').classList.add('isActive');
</script>
@endpush

</x-app-layout>