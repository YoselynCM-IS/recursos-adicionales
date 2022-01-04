export default {
    data(){
        return {
            load: false,
            roles: []
        }
    },
    created: function(){
        this.get_roles();
    },
    methods: {
        get_roles(){
            this.load = true;
            axios.get('/users/get_roles').then(response => {
                this.roles.push({
                    value: null, text: 'Selecciona una opción', disabled: true
                });
                response.data.forEach(r => {
                    if(r.id != 1) this.roles.push({ value: r.id, text: `${r.role}` });
                });
                this.load = false;
            }).catch(error => {
                this.load = false;
            });
        }
    },
}