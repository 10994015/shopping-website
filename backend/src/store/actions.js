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

export function getProducts({commit}){
    commit('setProducts', [true])
    return axiosClient.get('product').then(res=>{
        commit('setProducts', [false, res.data]);
    }).catch(()=>{
        commit('setProducts', [false])
    })
}