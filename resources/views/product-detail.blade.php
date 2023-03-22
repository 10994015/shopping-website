<x-app-layout>
    <div class="product-detail">
        <div class="product">
            <div class="product-image">
                <div class="imgBox">
                    <div class="magnifying">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <img src="/images/plant3-free-img.jpg" alt="" />
                </div>
            </div>
            <div class="product-intro">
                <a href="/products" class="category-name">椅子</a>
                <h1 class="product-title">黑色扶手椅</h1>
                <div class="price-list">
                    <p class="ori-price">$1000</p>
                    <p class="sale-price">$1000</p>
                    <span>+免運費</span>
                </div>
                <p class="short-description">Neque porro quisquam est, qui dolore ipsum quia dolor sit amet, consectetur adipisci velit, sed quia non numquam eius modi tempora incidunt lores ta porro ame. Neque porro quisquam est, qui dolore ipsum quia dolor sit amet....</p>
                <div class="add-cart">
                    <div class="input-number">
                        <button class="decrement" id="decrement" onclick="stepper(this)">-</button>
                        <input type="number" value="1" id="cart-number" min="1" max="100" step="1" />
                        <button class="increment" id="increment" onclick="stepper(this)">+</button>
                    </div>
                    <button type="button" class="addtocart">加入購物車</button>
                </div>
                <div class="category">Category: <a href="/products">椅子</a></div>
            </div>
        </div>
        <div class="detailed">
            <div class="detailed-btn">
                <button class="active" id="desctiption-btn">商品描述</button>
                <button id="comment-btn">商品評論 (0)</button>
            </div>
            <div class="description">
                <p>Auctor eros suspendisse tellus venenatis sodales purus non pellentesque amet, nunc sit eu, enim fringilla egestas pulvinar odio feugiat consectetur egestas magna pharetra cursus risus, lectus enim eget eu et lobortis faucibus。

                    Eget odio justo ut scelerisque purus non aliquam adipiscing amet condimentum ligula diam erat sodales pharetra accumsan pellentesque at sem at eget ac hendrerit odio enim felis sit augue lorem egestas dictum vestibulum a etiam nisi, elit augue volutpat porta scelerisque nullam at leo faucibus cursus met.
                    
                    Viverra nunc iaculis id sed diam nam quam id sapien pellentesque quam sed eu augue id ac tempus aliquam facilisis vivamus eget nisi id。
                </p>
            </div>
            <div class="comment">
                <p class="null-comment">還沒有任何評論。</p>
                <form action="">
                    <h3>成為第一個評論"黑色扶手椅"的人</h3>
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
            <div class="belike-list">
                @for($n=0;$n<4;$n++)
                <div class="item">
                    <div class="add-cart">
                        <i class="fa-solid fa-bag-shopping"></i>
                        <div class="loading"></div>
                        <input type="hidden" value="1" class="productId" />
                    </div>
                    <div class="sale-tag">Sale!</div>
                    <div class="toolbox">Add to cart</div>
                    <img src="/images/plant3-free-img.jpg" alt="" />
                    <small>椅子</small>
                    <h3>黑色扶手椅</h3>
                    <span><i class="fa-solid fa-star"></i>4.7</span>
                    <div class="price-row"><span class="price">$900</span><span class="sale-price">$700</span></div>
                </div>
                @endfor
            </div>
        </div>
       
    </div>

@push('scripts')
<script>
    document.querySelector('header').classList.add('isActive');
    const decrement = document.querySelector('.decrement')
    const increment = document.querySelector('.increment')
    const cartNumber = document.querySelector('#cart-number')
    const desctiptionBtn = document.querySelector('#desctiption-btn')
    const commentnBtn = document.querySelector('#comment-btn')
    const description = document.querySelector('.description')
    const comment = document.querySelector('.comment')
    function stepper(e){
        console.log(e);
        if(e.id  === "increment"){
            (cartNumber.value < 100) ? cartNumber.value++ : null; 
        }else{
            (cartNumber.value > 1) ? cartNumber.value-- : null; 
        }
    }
    desctiptionBtn.addEventListener('click', e=>{
        commentnBtn.classList.remove('active')
        desctiptionBtn.classList.add('active')
        comment.style.display = "none"
        description.style.display = "block"
    })
    commentnBtn.addEventListener('click', e=>{
        desctiptionBtn.classList.remove('active')
        commentnBtn.classList.add('active')
        description.style.display = "none"
        comment.style.display = "block"
    })
</script>

<script type="text/javascript" >
    
    const addCart = document.getElementsByClassName('add-cart');
    const belikeList = document.querySelector('.belike-list');
    
    const belikeItem  = belikeList.querySelectorAll('.item');
    
    const addCartFn = (e)=>{
        e.stopPropagation();
        if (e.target.tagName  == "I") return  e.target.parentNode.click();
        e.target.querySelector('.fa-solid').style.display = "none";
        e.target.querySelector('.loading').style.display = "block";
        setTimeout(() => {
            e.target.querySelector('i').style.display = "block";
            e.target.querySelector('.loading').style.display = "none";
        }, 1000);
    }
    const pushProductPageFn = ()=>{
        window.location.href = "/product-detail";
    }
    for(let i=0;i<addCart.length;i++){
        addCart[i].addEventListener('click', addCartFn)
    }
    for(let i=0;i<belikeItem.length;i++){
        belikeItem[i].addEventListener('click', pushProductPageFn)
    }
    </script>
@endpush

</x-app-layout>