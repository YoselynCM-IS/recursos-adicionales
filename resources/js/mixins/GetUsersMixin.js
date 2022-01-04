export default {
    data(){
        return {
            load: false,
            users: {} ,
            role_id: null,
            // BUSQUEDA POR LIBRO
            libro: null,
            libros: [],
            libro_id: null,
            // BUSQUEDA POR ESTADO
            estado: null,
            estados: [
                { value: null, text: 'Buscar por estado', disabled: true },
                { value: 'nuevo', text: 'nuevo' },
                { value: 'activo', text: 'activo' },
                { value: 'vencido', text: 'vencido' },
                { value: 'deshabilitado', text: 'deshabilitado' }
            ]
        }
    },
    methods: {
        // OBTENER PAGINADO
        get_results(page = 1){
            if(this.libro != null) this.http_libro(page);
            if(this.edtado != null) this.http_estado(page);
            else this.http_users(page);
        },
        // ASIGNAR ROL
        get_users(role_id){
            this.role_id = role_id;
            this.set_values(null, null, null);
            this.http_users();
        },
        // ASIGNAR VALORES A LAS VARIABLES DE BUSQUEDA
        set_values(libro_id, libro, estado){
            this.libro_id = libro_id;
            this.libro = libro;
            this.estado = estado;
        },
        // OBTENER USUARIOS
        http_users(page = 1){
            this.load = true;
            axios.get(`/users/get_users?page=${page}`, {params: {role_id: this.role_id}}).then(response => {
                this.users = response.data;
                this.load = false;
            }).catch(error => {
                this.load = false;
            });
        },
        // MOSTRAR LIBROS
        showBooks(){
            axios.get('/libros/show', {params: {libro: this.libro}}).then(response => {
                this.libros = response.data;
            }).catch(error => { });
        },
        // SELECCIONAR LIBRO
        selectBook(libro){
            this.set_values(libro.id, libro.libro, null);
            this.http_libro();
        },
        // OBTENER LIBROS
        http_libro(page = 1){
            this.load = true;
            axios.get(`/users/by_libro?page=${page}`, {params: {libro_id: this.libro_id, role_id: this.role_id}}).then(response => {
                this.users = response.data;
                this.libros = [];
                this.load = false;
            }).catch(error => {
                this.load = false;
            });
        },
        // OBTENER USUARIOS POR ESTADO
        http_estado(page = 1){
            this.load = true;
            axios.get(`/users/by_estado?page=${page}`, {params: {estado: this.estado, role_id: this.role_id}}).then(response => {
                this.users = response.data;
                this.libro_id = null;
                this.libro = null;
                this.load = false;
            }).catch(error => {
                this.load = false;
            });
        }
    }
}