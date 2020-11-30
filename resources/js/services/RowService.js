class RowService {
    /**
     * @param page
     * @return {Promise<AxiosResponse<any>>}
     */
    static list(page) {
        return axios.get('/api/rows/list?page=' + page);
    }
}

export default RowService;
