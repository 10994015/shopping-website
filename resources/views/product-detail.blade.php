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
                addCartLoading:false,
                productItem:{{json_encode($product)}},
                quantity:1,
                isFavorite: {{$favorite}},
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
                    if(!this.addCartLoading){
                        this.addCartLoading = true;
                        if(this.quantity <= 0 ){
                            this.quantity = 1;
                        }
                        axios.post('/cart/add/' + slug,{product:this.productItem, quantity:this.quantity}).then(res=>{
                            this.$dispatch('cart-change', res.data)
                            this.$dispatch('shop-add-change', res.data)
                        });
                        setTimeout(()=>{
                            this.addCartLoading = false;
                        },1000)
                    }
                },
                init(){
                },
                addFavourite(){
                    axios.post('/add-favorite' ,{id: this.productItem.id}).then(res=>{
                        if(res.status === 200){
                            this.isFavorite = true
                        }
                    })
                },
                removeFavourite(){
                    axios.post('/remove-favorite' ,{id: this.productItem.id}).then(res=>{
                        if(res.status === 204){
                            this.isFavorite = false
                        }
                    })
                }
            }">
                <a href="/store/{{$product->category->id}}" class="category-name">{{$product->category->name}}</a>
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
                <div class="favourite">
                    <template x-if="isFavorite">
                        <i class="fa-solid fa-heart" @click="removeFavourite"></i>
                    </template>
                    <template x-if="!isFavorite">
                        <i class="fa-regular fa-heart" @click="addFavourite"></i>
                    </template>
                </div>
                <div class="add-cart">
                    <div class="input-number">
                        <button class="decrement" id="decrement" x-on:click="decrementFn">-</button>
                        <input type="number"  :value="quantity" id="cart-number" min="1" max="100" step="1" x-on:change="changeQuantity" />
                        <button class="increment" id="increment" x-on:click="incrementFn">+</button>
                    </div>
                    <button type="button" class="addtocart" x-on:click="addToCart('{{$product->slug}}')"><span x-show="!addCartLoading">加入購物車</span> <span x-show="addCartLoading" class="loading"></span> </button>
                </div>
                <div class="category">Category: <a href="/store/{{$product->category->id}}">{{$product->category->name}}</a></div>
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
                <button x-bind:class="{'active':!isShowDescription}" id="comment-btn" @click="openComment">商品評論 ({{$count}})</button>
            </div>
            <div class="description" x-show="isShowDescription">
                <p>
                    {!! nl2br($product->description) !!}
                </p>
            </div>
            <div class="comment" x-show="!isShowDescription" x-data="{
                comments:{{json_encode($comments)}},
                rating:0,
                comment:'',
                loading:false,
                success:false,
                fail:false,
                failtext:'',
                isNoComment:true,
                init(){
                    console.log(this.comments)
                },
                store(){
                    this.loading = true
                    this.fail = false
                    this.success = false
                    axios.post('/comment', {
                        comment: this.comment,
                        rating: this.rating,
                        product_id:{{$product->id}}
                    }).then(res=>{
                        if(res.status===201){
                            this.comments = res.data.comments
                            this.success = true
                            this.isNoComment = false
                        }
                        this.rating = 0
                        this.comment = ''
                        this.loading = false
                    }).catch(err=>{
                        this.loading = false
                        if(err.response.status===401){
                            alert('請先登入！')
                            this.failtext = '發送失敗！請先登入！'
                            return window.location.href='/login'
                        }else{
                            this.fail = true
                            this.failtext = error.response.message
                        }
                    })
                },
                deleteFn(id){
                    if(confirm('刪除了就不能復原了！\n確定刪除該評論？')){
                        console.log(id)
                        axios.delete('/comment/' + id).then(res=>{
                            alert(res.data.message)
                            this.comments = res.data.comments
                        })
                    }
                }
            }">
                <div class="comment-list">
                    <template x-for="comment in comments" :key="comment.id">
                        <div class="comment-item">
                            <p class="name" x-text="comment.email"></p>
                            @if($user !== null)
                            <template x-if="comment.user_id === {{$user->id}}">
                                <button class="delete float-right" @click="deleteFn(comment.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-red-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </template>
                            @endif
                            <div class="score">
                            <span :class="(comment.score >= 1) ? 'full' : ''"> &#9733;</span>
                            <span :class="(comment.score >= 2) ? 'full' : ''"> &#9733;</span>
                            <span :class="(comment.score >= 3) ? 'full' : ''"> &#9733;</span>
                            <span :class="(comment.score >= 4) ? 'full' : ''"> &#9733;</span>
                            <span :class="(comment.score >= 5) ? 'full' : ''"> &#9733;</span>
                            </div>
                            <span class="date" x-text="comment.created_at"></span>
                            <article x-text="comment.comment"></article>
                        </div>
                    </template>
                </div>
                @if($count <= 0)
                <p class="null-comment">還沒有任何評論。</p>
                @endif
                <form >
                    @if($count <= 0)
                    <template x-if="isNoComment">
                        <h3>成為第一個評論"{{$product->title}}"的人</h3>
                    </template>
                    @endif
                    <label>您的電子郵件地址不會被公開。 必填字段已標記*</label>
                    <label class="flex items-center">您的評價* 
                        <div class="rating">
                            <input type="radio" id="star5" name="rating" x-model="rating" value="5" />
                            <label for="star5">&#9733;</label>
                            <input type="radio" id="star4" name="rating" x-model="rating" value="4" />
                            <label for="star4">&#9733;</label>
                            <input type="radio" id="star3" name="rating" x-model="rating" value="3" />
                            <label for="star3">&#9733;</label>
                            <input type="radio" id="star2" name="rating" x-model="rating" value="2" />
                            <label for="star2">&#9733;</label>
                            <input type="radio" id="star1" name="rating" x-model="rating" value="1" />
                            <label for="star1">&#9733;</label>
                          </div>
                    </label>
                    <label for="">
                        <p>您的評論*</p>
                        <textarea x-model="comment"></textarea>
                    </label>
                    <label for="">
                        <button type="button" @click="store()" :disabled="(loading) ? true : false">
                            <span x-show="!loading">提交</span>
                            <span x-show="loading">
                                <svg v-if="categoryLoading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </label>
                    <label for="">
                        <span x-show="success" class="text-green-600">發送成功！感謝您的評論！</span>
                        <span x-show="fail" class="text-red-600">發送失敗！請填寫星等以及評論！</span>
                    </label>
                </form>
            </div>
        </div>
        <div class="belike">
            @if(count($products) > 0)
            <h3>您可能會喜歡</h3>
            @endif
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
                                    axios.post('/cart/add/'+ slug,{product:this.productItem, quantity:1}).then(res=>{
                                        this.$dispatch('cart-change',res.data)
                                        this.$dispatch('shop-add-change', res.data)
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