import axiosClient from "../axios";

export function getUser({commit}){
    return axiosClient.get('/user').then(res=>{
        commit('setUser', res.data)
        return res;
    })
}

export function login({commit}, data){
    return axiosClient.post('login', data).then(res=>{
        commit('setUser', res.data.user);
        commit('setToken', res.data.token);
        return data;
    })
}

export function logout({commit}){
    return axiosClient.post('logout').then(res=>{
        commit('setToken', null);
        return res;
    })
}

export function getProducts({commit}, {url = null, search = '', perPage = 10, sort_field, sort_direction}){
    commit('setProducts', [true]);
    url = url || '/product';
    return axiosClient.get(url, {params:{search, per_page:perPage, sort_field, sort_direction}}).then(res=>{
        commit('setProducts', [false, res.data]);
    }).catch(err=>{
        commit('setProducts', [false]);
    })
}