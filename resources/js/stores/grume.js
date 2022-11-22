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
        },
        search: {
            text: ''
        },
        toBeDeleted: new Set()
    }),
    getters: {
        getRecords: (state) => state.records,
        getFilters: (state) => state.filters,
        getSort: (state) => state.sort,
        getSearch: (state) => state.search,
        getTotalRecords: (state) => state.totalRecords,
        getPerPage: (state) => state.paginate.perPage
    },
    actions: {
        init() {
            if (!Grume.store)
                Grume.store = this
        },
        addFilter(filter) {
            this.filters.set(filter.column, { min: filter.min, max: filter.max })
            this.get()
        },
        deleteFilter(column) {
            this.filters.delete(column)
            this.get()
        },
        setSort(sort) {
            this.sort.column = sort.column
            this.sort.order = sort.order
            this.get()
        },
        setPaginate({ from, to }) {
            this.paginate.from = from;
            this.paginate.to = to
            this.get()
        },
        setSearch(text) {
            this.search.text = text
            this.get()
        },
        addToBeDeleted(record) {
            this.toBeDeleted.add(record)
        },
        removeFromToBeDeleted(record) {
            this.toBeDeleted.delete(record)
        },
        async get() {
            try {

                const response = await axiosClient.post('/grumes', {
                    filters: Object.fromEntries(this.filters),
                    sort: this.sort,
                    paginate: {
                        from: this.paginate.from,
                        to: this.paginate.to
                    },
                    search: this.search.text
                })
                this.records = response.data.records
                this.totalRecords = response.data.totalRecords
            } catch (error) {
                console.log(error)
            }
        },
        async create(record) {
            try {
                const response = await axiosClient.post('/grumes/new', record)
                this.get()
                return response.data
            } catch (error) {
                console.log(error)
            }
        },
        async show(record){
            try {
                const response = await axiosClient.get(`/grumes/${record}`)
                return response.data
            } catch (error) {
                console.log(error)
            }
        },
        async edit(record) {
            try {
                const response = await axiosClient.put(`/grumes/${record.number}`, record)
                this.get()
                return response.data
            } catch (error) {
                console.log(error)
            }
        },
        async delete() {
            try {
                const response = await axiosClient.post('/grumes/delete', { records: [...this.toBeDeleted] })
                this.toBeDeleted.clear()
                this.get()
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
        async download(records) {
            try {
                const response = await axiosClient.post('/grumes/download', { records: records },
                    { responseType: 'blob' })
                return response
            } catch (error) {
                console.log(error)
            }
        }
    }

})
