function datatableCargar(tipo = 'data', tabla = 1) {
    tipoActual = tipo;
    app.tipoActual = tipoActual;
    app.tabla = tabla;
    app.getDatatableCompromisosGETServerSide();
}