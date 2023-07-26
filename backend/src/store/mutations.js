export function setUser(state, user){
    state.user.data = user;
}

export function setToken(state, token){
    state.user.token = token;
    if(token){
        sessionStorage.setItem('TOKEN', token);
    }else{
        sessionStorage.removeItem('TOKEN');
    }
}

export function setProducts(state, [loading, res=null]){
    console.log(res);
    if(res){
        state.products = {
            data: res.data,
            links: res.meta.links,
            total: res.meta.total,
            limit: res.meta.per_page,
            from: res.meta.from,
            to: res.meta.to,
            page: res.meta.current_page,
        }
    }
    state.products.loading = loading;
}
export function setOrders(state, [loading, res=null]){
    console.log(res);
    if(res){
        state.orders = {
            data: res.data,
            links: res.meta.links,
            total: res.meta.total,
            limit: res.meta.per_page,
            from: res.meta.from,
            to: res.meta.to,
            page: res.meta.current_page,
        }
    }
    state.orders.loading = loading;
}
export function setCategories(state, categories){
    if(categories){
        state.categories.data = categories;
    }
}