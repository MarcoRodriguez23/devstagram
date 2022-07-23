import Dropzone from "dropzone";
import { size } from "lodash";

Dropzone.autoDiscover = false;

if(document.querySelector('#dropzone')){
    const dropzone = new Dropzone('#dropzone',{
        dictDefaultMessage: 'Sube aqui tu imagen',
        acceptedFiles: ".png, .jpg, .jpeg, .gif",
        addRemoveLinks: true,
        dictRemoveFile: 'Borrar archivo',
        maxFiles: 1,
        uploadMultiple: false,
    
        init: function(){
            if(document.querySelector('[name="imagen"]').value.trim()){
                const imagenPublicada = {};
                imagenPublicada.size = 1234;
                imagenPublicada.name = document.querySelector('[name="imagen"]').value;
    
                this.options.addedfile.call(this,imagenPublicada);
                this.options.thumbnail.call(this,imagenPublicada, `/uploads/${imagenPublicada.name}`);
    
                imagenPublicada.previewElement.classList.add('dz-success','dz-complete');
            }
        }
    })
    
    // dropzone.on('sending',function(file, xhr, formData){
    //     console.log(file);
    // })
    
    dropzone.on('success',function(file, response){
        console.log(response.imagen);
        document.querySelector('[name="imagen"]').value = response.imagen;
    })
    
    // dropzone.on('error',function(file, message){
    //     console.log(message);
    // })
    
    dropzone.on('removedfile',function(file){
        // console.log("Eliminado");
        document.querySelector('[name="imagen"]').value = "";
    })
}

const cambiarPassword = document.querySelector('#cambiarpassword');
if(cambiarPassword){
    cambiarPassword.addEventListener('change',function(){
        const casillas = document.querySelector('#casillasPassword');
        if (cambiarPassword.checked) {
            casillas.classList.remove('ocultar');
        } else {
            casillas.classList.add('ocultar');
        }
    });
}
