jQuery(function($) {
    // Switchery
    $('[data-toggle="switchery"]').each(function (idx, obj) {
        new Switchery($(this)[0], $(this).data());
    });

    // Touchspin
    var defaultOptions = {};
    $('[data-toggle="touchspin"]').each(function (idx, obj) {
        var objOptions = $.extend({}, defaultOptions, $(obj).data());
        $(obj).TouchSpin(objOptions);
    });

    //Dropify
    $('.dropify').dropify({
        messages: {
            'default': 'Arrastra un archivo o haz click',
            'replace': 'Arrastra un archivo o haz click para reemplazar',
            'remove': 'Eliminar',
            'error': 'Ha habido un error en la subida.'
        },
        error: {
            'fileSize': 'El archivo es demasiado grande (4 Mb máximo).',
            'fileExtension': 'Solo se permiten archivos de imagenes ({{ value }}).'
        }
    });


    
    $(window).resize(function(){
        if($(window).width()>990){
            $('#layout-wrapper > .vertical-menu').show();
        }else{
            $('#layout-wrapper > .vertical-menu').hide();
        }
    });
    $('#vertical-menu-btn').click(function(){
        $('#layout-wrapper > .vertical-menu').show();

        $('#vertical-menu-close-btn').click(function(){
            $('#layout-wrapper > .vertical-menu').hide();
        });
    });
    
});



function format_date(date){
    var dd = date.getDate(); 
    var mm = date.getMonth() + 1;
    var hh = date.getHours();
    var min = date.getMinutes();
    var ss = date.getSeconds();      
    var yyyy = date.getFullYear(); 
    if (dd < 10)  dd = '0' + dd; 
    if (mm < 10)  mm = '0' + mm;
    if (hh < 10)  hh = '0' + hh;
    if (min < 10)  min = '0' + min;
    if (ss < 10)  ss = '0' + ss; 
     
    return  yyyy+'-'+mm +'-'+dd+' '+hh+':'+min+':'+ss; 
}

function validar_email(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
