'use strict';
const cfg=JSON.parse(document.getElementById('hus-config').textContent);
const $=id=>document.getElementById(id);
let report=null, inspected=null;
function notice(text,error=false){ const n=$('notice'); n.hidden=false;n.className='notice '+(error?'error':'success');n.textContent=text; }
async function api(path,values=null){
 const options={credentials:'same-origin',headers:{'Accept':'application/json'}};
 if(values!==null){const body=values instanceof FormData?values:new URLSearchParams(values);body.set(cfg.csrfName,cfg.csrf);options.method='POST';options.body=body;}
 let r;try{r=await fetch(cfg.base+'api/'+path,options);}catch(e){throw new Error('Sin conexión. Consulta el HU antes de reintentar: no se ha confirmado el resultado.');}
 let result;try{result=await r.json();}catch(e){throw new Error('Respuesta no confirmada. Consulta el estado antes de reintentar.');}
 if(!r.ok || !result.ok)throw new Error((result.message||'Operación no confirmada')+(result.correlation?' · Ref. '+result.correlation:''));
 return result.data;
}
function table(rows,container,meta={},editable=false){
 container.replaceChildren();
 if(!rows.length){const p=document.createElement('p');p.className='empty';p.textContent='Sin registros.';container.append(p);return;}
 const wrap=document.createElement('div');wrap.className='table-wrap';const t=document.createElement('table');
 const keys=Object.keys(rows[0]);const head=document.createElement('thead'),tr=document.createElement('tr');
 for(const k of keys){const th=document.createElement('th');th.textContent=k;tr.append(th);}if(editable){const th=document.createElement('th');th.textContent='Corrección';tr.append(th);}head.append(tr);t.append(head);
 const body=document.createElement('tbody');
 const show=(v,k)=>meta.round && meta.numeric.includes(k) && v!==null && v!==''?String(Math.ceil(Number(v))):String(v??'');
 const add=(row,total=false)=>{const tr=document.createElement('tr');if(total)tr.className='total';for(const k of keys){const td=document.createElement('td');const value=show(row[k],k);if(value.length>180){const details=document.createElement('details'),summary=document.createElement('summary'),pre=document.createElement('pre');summary.textContent='Ver contenido';pre.textContent=value;details.append(summary,pre);td.append(details);}else{td.textContent=value;}if(meta.numeric?.includes(k))td.className='number';tr.append(td);}
 if(editable){const td=document.createElement('td');if(!total){const b=document.createElement('button');b.className='secondary small';b.textContent='Editar';b.addEventListener('click',()=>edit(row.Id));td.append(b);}tr.append(td);}body.append(tr);};
 rows.forEach(r=>add(r));if(meta.totals)add(meta.totals,true);t.append(body);wrap.append(t);container.append(wrap);
}
async function loadReport(){
 if(!$('report'))return;
 try{report=await api('report/'+cfg.page);renderReport();}catch(e){notice(e.message,true);}
}
function renderReport(){
 const q=($('search')?.value||'').toLocaleLowerCase();const rows=report.rows.filter(r=>Object.values(r).some(v=>String(v??'').toLocaleLowerCase().includes(q)));
 table(rows,$('report'),report,cfg.page==='total'&&cfg.role===3);
 if(report.units){const heading=document.createElement('h2');heading.textContent='HUs capturados y pendientes';heading.style.marginTop='28px';const detail=document.createElement('div');$('report').append(heading,detail);const units=report.units.filter(r=>Object.values(r).some(v=>String(v??'').toLocaleLowerCase().includes(q)));table(units,detail);}

 if($('summary')){$('summary').replaceChildren();const values=cfg.page==='progress'?report.totals:{Registros:report.rows.length};for(const [k,v]of Object.entries(values||{})){if(!['HUs','Captured','Pending','Registros'].includes(k))continue;const card=document.createElement('div');card.className='stat';const label=document.createElement('span');label.textContent=k;const n=document.createElement('strong');n.textContent=v;card.append(label,n);$('summary').append(card);}}
}
async function edit(id){try{const data=await api('editable',{id});const f=$('edit-form');f.elements.id.value=data.row.Id;f.elements.revision.value=data.revision;f.elements.itemcode.value=data.row.itemcode;f.elements.qty.value=data.row.qty;f.elements.reason.value='';$('edit-dialog').showModal();}catch(e){notice(e.message,true);}}
function busy(form,value){form.querySelectorAll('button').forEach(b=>b.disabled=value);}
$('capture-location')?.addEventListener('keydown',async e=>{if(e.key!=='Enter')return;e.preventDefault();try{const r=await api('checklocation',{location:e.target.value});notice('Locación válida · '+r.warehouse);$('capture-hu').focus();}catch(err){notice(err.message,true);}});
$('capture-form')?.addEventListener('submit',async e=>{e.preventDefault();const f=e.currentTarget;busy(f,true);const hu=f.elements.hu.value.trim();try{const r=await api('capture',new FormData(f));notice(`${r.message} ${r.partidas} partidas · Ref. ${r.correlation}`);f.elements.hu.value='';$('lookup-hu').value=hu;await lookup(hu,false);f.elements.location.value='';f.elements.location.focus();}catch(err){notice(err.message,true);}finally{busy(f,false);}});
async function lookup(hu,announce=true){
 inspected=null;if($('audit-form'))$('audit-form').hidden=true;
 $('lookup-summary').textContent='';$('lookup-table').replaceChildren();
 const data=await api('lookup',{hu});inspected={...data,hu};
 const used=data.rows.filter(r=>r.Status==='Used').length;
 $('lookup-summary').textContent=`HU ${hu} · ${data.rows.length} partidas · ${used===data.rows.length?'Capturado':used?'Captura previa parcial; requiere revisión':'Pendiente'}`;
 table(data.rows,$('lookup-table'));
 if($('audit-form')){const f=$('audit-form');f.hidden=false;f.elements.warehouse.value=data.capture?.warehouse||data.rows[0].WAREHOUSE||'';f.elements.location.value=data.capture?.location||data.rows[0].Location||'';f.elements.result.value='';f.elements.observations.value='';}
 if(announce)notice('Contenido cargado.');
}
$('lookup-form')?.addEventListener('submit',async e=>{e.preventDefault();busy(e.currentTarget,true);try{await lookup($('lookup-hu').value.trim());}catch(err){notice(err.message,true);}finally{busy(e.currentTarget,false);}});
$('audit-form')?.addEventListener('submit',async e=>{e.preventDefault();if(!inspected)return;const f=e.currentTarget;busy(f,true);try{const body=new FormData(f);body.set('hu',inspected.hu);body.set('revision',inspected.revision);const r=await api('audit',body);notice(r.message);f.hidden=true;inspected=null;await loadReport();}catch(err){notice(err.message,true);}finally{busy(f,false);}});
$('edit-form')?.addEventListener('submit',async e=>{e.preventDefault();const f=e.currentTarget;busy(f,true);try{const r=await api('correct',new FormData(f));$('edit-dialog').close();notice(r.message);await loadReport();}catch(err){notice(err.message,true);}finally{busy(f,false);}});
$('cancel-edit')?.addEventListener('click',()=>$('edit-dialog').close());
$('import-form')?.addEventListener('submit',async e=>{e.preventDefault();const f=e.currentTarget;busy(f,true);notice('Validando archivo completo e importando…');try{const r=await api('importfile',new FormData(f));notice(r.message);f.reset();}catch(err){notice(err.message,true);}finally{busy(f,false);}});
$('refresh')?.addEventListener('click',loadReport);$('search')?.addEventListener('input',renderReport);
loadReport();

const requestedHU=new URLSearchParams(window.location.search).get('hu');if(requestedHU&&$('lookup-form')){$('lookup-hu').value=requestedHU;lookup(requestedHU).catch(e=>notice(e.message,true));}
