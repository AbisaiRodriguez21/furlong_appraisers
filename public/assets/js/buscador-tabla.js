/**
 * Buscador de texto libre para las tablas de listado (Clientes, Trabajos,
 * Avalúos, Cobranza, etc). Filtra por coincidencia en cualquier parte del
 * texto de la columna indicada, sin distinguir mayúsculas/minúsculas -
 * así "ABISAI" encuentra "ABDIEL ABISAI" aunque no sea la primera palabra.
 */
function buscarEnTabla(txt, inputId, tablaId, columna) {
    var input = document.getElementById(inputId);
    if (input) {
        input.value = txt.toUpperCase();
    }

    var termino = txt.toUpperCase().trim();
    var filas = document.getElementById(tablaId).getElementsByTagName('tr');

    for (var i = 0; i < filas.length; i++) {
        var celda = filas[i].getElementsByTagName('td')[columna];
        if (!celda) {
            continue;
        }
        var texto = (celda.textContent || celda.innerText || '').toUpperCase();
        filas[i].style.display = (termino === '' || texto.indexOf(termino) !== -1) ? '' : 'none';
    }
}
