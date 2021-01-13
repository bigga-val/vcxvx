$(document).ready(function(){
    let input = $('#date_recherche')
    console.log("bien")
    input.on('change', function (){
        console.log($(this).val())
        $.ajax({
            type:'post',
            url:'historique_paiement',
            data: {'date':$(this).val()} ,
            success: function (data){
                console.log(data)
                let cpt = 1
                let tbody = ''
                for(let i = 0; i<data.length; i++)
                {
                    cpt = cpt + i
                    tbody += "<tr>"
                    tbody+="<td>"+ cpt +"</td>"
                    tbody+="<td>"+ data[i]['date'] +"</td>"
                    tbody+="<td>"+ data[i]['nom_complet'] +"</td>"
                    tbody+="<td>"+ data[i]['classe']+' '+data[i]['option'] +"</td>"
                    tbody+="<td>"+ data[i]['frais'] +"</td>"
                    tbody+="<td>"+ data[i]['montant'] +"</td>"
                    tbody+="<td>"+ data[i]['montant_paye'] +"</td>"
                    tbody+="<td>"+ data[i]['montant_reste'] +"</td>"
                    tbody+="<td> <a href=''>Facturer</a></td>"
                    tbody+="</tr>"
                }
                console.log(tbody)
                $("#tbody").html(tbody)

            },
            error: function (e){
                console.error(e)
            }
        })
    })
})