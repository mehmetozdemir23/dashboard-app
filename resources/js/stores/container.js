import { defineStore } from "pinia";
import axiosClient from "../axios";
import { Container } from "../models/Container";

export const useContainerStore = defineStore('container', {
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
            if (!Container.store)
                Container.store = this
        },
        addFilter(filter) {
            this.filters.set(filter.column, { min: filter.min, max: filter.max })
            this.get()
        },
        deleteFilter(column) {
            console.log(column)
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

                const response = await axiosClient.post('/containers', {
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
        async show(record){
            try {
                const response = await axiosClient.get(`/containers/${record}`)
                return response.data
            } catch (error) {
                console.log(error)
            }
        },
        async create(record) {
            try {
                const response = axiosClient.post('/containers/new', record)
                this.get()
                return response.data
            } catch (error) {
                console.log(error)
            }
        },
        async edit(record) {
            try {
                const response = await axiosClient.put(`/containers/${record.number}`, record)
                this.get()
                return response.data
            } catch (error) {
                console.log(error)
            }
        },
        async delete() {
            try {
                console.log(this.toBeDeleted);
                const response = await axiosClient.post('/containers/delete', { records: [...this.toBeDeleted] })
                this.toBeDeleted.clear()
                this.get()
                return response.data
            } catch (error) {
                console.log(error)
            }
        },
        async upload(file) {
            try {
                const response = await axiosClient.post('/containers/upload', { file: file }, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                return response.data
            } catch (error) {
                console.log(error)
            }
        },
        async download(record) {
            try {
                const response = await axiosClient.get(`/containers/download/${record.number}`,
                    { responseType: 'blob' })
                return response
            } catch (error) {
                console.log(error)
            }
        }
    }

})
