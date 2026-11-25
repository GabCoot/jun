const campos = ['curp', 'nombres', 'apellidos', 'fecha_nacimiento', 'sexo', 'estado_civil', 'telefono_celular', 'correo_electronico', 'codigo_postal', 'estado', 'municipio', 'colonia', 'calle', 'whatsapp_vinculado', 'whatsapp_opt_in'];
const el = id => document.getElementById(id);
async function api(ruta, opciones = {}) { const r = await fetch('api/' + ruta, { ...opciones, cache: 'no-store' }); const j = await r.json(); if (!r.ok || j.error)
    throw new Error(j.error || 'Error de consulta'); return j; }
function mensaje(id, t, error = false) { el(id).textContent = t; el(id).className = 'mt-2 small ' + (error ? 'text-danger' : 'text-success'); }
function mostrar(d) { el('id_expediente').value = d.id_expediente; for (const c of campos) {
    const e = el(c);
    if (e.type === 'checkbox')
        e.checked = Number(d[c]) === 1;
    else
        e.value = d[c] ?? '';
} el('tituloExpediente').textContent = [d.nombres, d.apellidos].filter(Boolean).join(' '); el('metaExpediente').textContent = 'Expediente ' + d.id_expediente; el('badgeEstatus').textContent = d.estatus_expediente ?? ''; el('badgeEstatus').className = 'badge ' + (d.estatus_expediente === 'completo' ? 'bg-success' : 'bg-warning text-dark'); el('panelExpediente').style.display = ''; mensaje('msgGuardar', ''); }
async function buscar() { const q = el('inputBuscar').value.trim(); if (!q) {
    mensaje('msgBuscar', 'Ingrese CURP o teléfono', true);
    return;
} try {
    mostrar((await api('buscar.php?q=' + encodeURIComponent(q))).data);
    mensaje('msgBuscar', 'Expediente encontrado');
}
catch (e) {
    el('panelExpediente').style.display = 'none';
    mensaje('msgBuscar', e.message, true);
} }
async function atender(id) { try {
    mostrar((await api('por_id.php?id=' + encodeURIComponent(id))).data);
    mensaje('msgBuscar', 'Expediente encontrado');
    el('panelExpediente').scrollIntoView({ behavior: 'smooth' });
}
catch (e) {
    mensaje('msgBuscar', e.message, true);
} }
async function guardar() { if (!el('formExpediente').reportValidity())
    return; const d = { id_expediente: Number(el('id_expediente').value) }; for (const c of campos) {
    const e = el(c);
    d[c] = e.type === 'checkbox' ? Number(e.checked) : e.value.trim();
} try {
    await api('guardar.php', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': document.querySelector('meta[name=panel-csrf]').content }, body: JSON.stringify(d) });
    mensaje('msgGuardar', 'Cambios guardados');
}
catch (e) {
    mensaje('msgGuardar', e.message, true);
} }
function limpiar() { el('formExpediente').reset(); el('panelExpediente').style.display = 'none'; el('inputBuscar').value = ''; mensaje('msgBuscar', ''); mensaje('msgGuardar', ''); }
function cargarIncompletos() { location.reload(); }
document.addEventListener('DOMContentLoaded', () => { el('inputBuscar')?.addEventListener('keydown', e => { if (e.key === 'Enter') {
    e.preventDefault();
    buscar();
} }); });
