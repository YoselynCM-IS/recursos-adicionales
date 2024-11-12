export default {
    methods: {
        // VEIRICAR SI SE PUEDE EDITAR EL RECURSO
        verify_recurso(recurso){
            if(recurso.includes('Games')) return false;
            if(recurso.includes('Flipbook')) return false;
            if(recurso == 'Enlaces') return false;
            if(recurso == 'Audios') return false;
            return true;
        }
    }
}