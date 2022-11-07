import { defineStore } from "pinia";
import axiosClient from "../axios";
import { Grume } from "../models/Grume";

export const useGrumeStore = defineStore('grume', {
    state: () => ({
        records: [],
        totalRecords: 0,
        filters: new Map(),
        sort: {
            column: 'number',
            order: 1
        },
        paginate: {
            from: 0,
            to: 3,
            perPage: 4
        }
    }),
    getters: {
        getRecords: (state) => state.records,
        getFilters: (state) => state.filters,
        getSort: (state) => state.sort,
        getTotalRecords: (state) => state.totalRecords,
        getPerPage: (state) => state.paginate.perPage
    },
    actions: {
        init(){
            if(!Grume.store)
                Grume.store = this
        },
        async fetchRecords() {
            try {

                const response = await axiosClient.post('/grumes', {
                    filters: Object.fromEntries(this.filters),
                    sort: this.sort,
                    paginate: {
                        from: this.paginate.from,
                        to: this.paginate.to
                    }
                })
                this.records = response.data.records
                this.totalRecords = response.data.totalRecords
            } catch (error) {
                console.log(error)
            }
        },
        addFilter(filter) {
            this.filters.set(filter.column,{min:filter.min,max:filter.max})
        },
        deleteFilter(column) {
            console.log(column)
            this.filters.delete(column)
        },
        setSort(sort) {
            this.sort.column = sort.column
            this.sort.order = sort.order
        },
        setPaginate({ from, to }) {
            this.paginate.from = from;
            this.paginate.to = to
        },
        async edit(record) {
            try {
                const response = await axiosClient.put(`/grumes/${record.number}`, record)
                return response.data
            } catch (error) {
                console.log(error)
            }
        },
        async delete(record) {
            try {
                const response = await axiosClient.delete(`/grumes/${record.number}`)
                return response.data
            } catch (error) {
                console.log(error)
            }
        },
        async upload(file) {
            try {
                const response = await axiosClient.post('/grumes/upload', { file: file }, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                return response.data
            } catch (error) {
                console.log(error)
            }
        },
        async download() {
            try {
                const response = await axiosClient.post('/grumes/download', {
                    filters: Object.fromEntries(this.filters),
                },
                    { responseType: 'blob' })
                return response
            } catch (error) {
                console.log(error)
            }
        }
    }

})
