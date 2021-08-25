//carregar lista de requerimentos
// window.onload= function() {
//     $.ajax({
//         url:'/requerimento',
//         type:"get",
//         dataType:'json',
//         data: {"filtro": "all" },
//         success: function(response){
//             $('tbody_').html(response.table_data);
//         }
//     });
// };

// window.selecionarFiltro = function(){
//     //area
//     var historySelectList = $('select#idSelecionarFiltro');
//     var $opcao = $('option:selected', historySelectList).val();
//     // console.log($opcao);
//     $.ajax({
//         url:'/requerimento',
//         type:"get",
//         dataType:'json',
//         data: {"id_area": $opcao},
//         success: function(response){
//             $('tbody').html(response.table_data);
//             // document.getElementById('idArea');
//         }
//     });
// }

// window.selecionarFiltroRequerimento = function($filtro){
//     // console.log($filtro);
//     $.ajax({
//         url:'/requerimento',
//         type:"get",
//         dataType:'json',
//         data: {"filtro": $filtro },
//         success: function(response){
//             $('tbody_').html(response.table_data);
//         }
//     });
// }

window.abrir_fechar_card_requerimento = function($valor){
    console.log($valor);
    if(document.getElementById($valor).style.display == "none"){
        document.getElementById($valor).style.display = "block";
    }else{
        document.getElementById($valor).style.display = "none";
    }
}

window.empresaId = function($empresaId) {
    console.log($empresaId);
    document.getElementById("inputSubmeterId").value = $empresaId;
    document.getElementById("submeterId").submit();
}

window.licencaAvaliacao = function($empresaId, $area, $requerimento) {
    console.log($empresaId);
    console.log($area);
    document.getElementById("licencaAvaliacao").value = $empresaId;
    document.getElementById("areaCnae").value = $area;
    document.getElementById("requerimento").value = $requerimento;
    document.getElementById("licenca").submit();
}

window.dispensaAvaliacao = function($empresaId, $area, $requerimento) {
    console.log($empresaId);
    console.log($area);
    document.getElementById("licencaAvaliacao2").value = $empresaId;
    document.getElementById("areaCnae2").value = $area;
    document.getElementById("requerimento2").value = $requerimento;
    document.getElementById("dispensa").submit();
}

// var arrayTemp = [];

// window.addRequerimento = function($id) {
//     if(arrayTemp.findIndex(element => element == $id) == -1){ //condicao para add o requerimento na lista

//         // innerText sempre pegará o primero texto da lista
//         var elemento = document.getElementById($id).innerText;
//         linha = montarLinhaInputRequerimento($id,elemento);
//         $('#adicionar').append(linha);
//         arrayTemp.push($id);
//     }
// }

// window.montarLinhaInputRequerimento = function(id,elemento){
//     console.log(elemento);
//     return " <div class='form-gerado cardMeuCnae'>\n"+
//     "           <div class='d-flex'>\n"+
//     "           <div class='mr-auto p-2'>\n"+
//     "               "+elemento+"\n"+
//     "               <input type='hidden' name='requerimentos[]' value='"+id+"'>\n"+
//     "           </div>\n"+
//     "           <div class='p-2'>\n" +
//     "               <button type='button' class='btn btn-danger' value='"+id+"' onclick='deletar(this)'>X</button>\n" +
//     "           </div>\n"+
//     "           <div>\n"+
//     "       </div>\n";
// }
