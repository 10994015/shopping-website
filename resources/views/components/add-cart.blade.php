<script type="text/javascript" >
    
    const addCart = document.getElementsByClassName('add-cart');
    const products = document.querySelector('.products-list');
    
    const productItem  = products.querySelectorAll('.item');
    
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
   
    const pushProductPageFn = (ev, slug)=>{
        window.location.href = `/product-detail/${slug}`;
    }
    for(let i=0;i<addCart.length;i++){
        addCart[i].addEventListener('click', addCartFn)
    }
    // for(let i=0;i<productItem.length;i++){
    //     productItem[i].addEventListener('click', pushProductPageFn)
    // }
</script>