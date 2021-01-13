$(document).ready(function (){
    let btn = $('#recherche')
    console.log('bien')
    btn.on('click', function (){
        let classe = $("#classe")
        let frais = $("#frais")
        console.log(frais.val())
        //console.log(classe.val())
        $.ajax({
            type:'post',
            url:'trier_eleves',
            data:{'frais': frais.val(), 'classe':classe.val()},
            success:function(data){
                //console.log(data)

                let cpt = 1
                let tbody = '<table class="table align-items-center table-flush">\n' +
                    '    <thead>\n' +
                    '        <th>#</th>\n' +
                    '        <th>Nom et Postnom</th>\n' +
                    '        <th>M. Payé</th>\n' +
                    '        <th>M. Resté</th>\n' +
                    '    </thead><tbody id="tbody">'
                if(data.length  == 0)
                {
                    tbody += "<tr class='text-center text-danger'><td colspan='4'>Aucun enregistrement disponible pour ce frais en cette classe</td></td>"
                    $("#tableau").html(tbody)
                }else{
                    for(let i = 0; i<data.length; i++)
                    {
                        cpt = cpt + i
                        tbody += "<tr>"
                        tbody+="<td>"+ cpt +"</td>"
                        tbody+="<td>"+ data[i]['nom_complet'] +"</td>"
                        tbody+="<td>"+ data[i]['montant_paye'] +"</td>"
                        tbody+="<td>"+ data[i]['montant_reste'] +"</td>"
                        tbody+="</tr>"
                    }
                    tbody+='</tbody></table>'
                    console.log(data)
                    $("#tableau").html(tbody)
                }

            },
            error:function(e){
                console.error(e)
            }
        })
    })
})