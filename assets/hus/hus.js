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
 if(cfg.page==='variation')renderVariation(rows);else if(cfg.page==='progress')renderProgress(rows);else table(rows,$('report'),report,cfg.page==='total'&&cfg.role===3);
 if(report.units && cfg.page!=='progress'){const heading=document.createElement('h2');heading.textContent='HUs capturados y pendientes';heading.style.marginTop='28px';const detail=document.createElement('div');$('report').append(heading,detail);const units=report.units.filter(r=>Object.values(r).some(v=>String(v??'').toLocaleLowerCase().includes(q)));table(units,detail);}

 if($('summary')){$('summary').replaceChildren();const values=cfg.page==='progress'?report.totals:{Registros:report.rows.length};for(const [k,v]of Object.entries(values||{})){if(!['HUs','Captured','Pending','Registros'].includes(k))continue;const card=document.createElement('div');card.className='stat';const label=document.createElement('span');label.textContent=k;const n=document.createElement('strong');n.textContent=v;card.append(label,n);$('summary').append(card);}}
}
async function edit(id){try{const data=await api('editable',{id});const f=$('edit-form');f.elements.id.value=data.row.Id;f.elements.revision.value=data.revision;f.elements.itemcode.value=data.row.itemcode;f.elements.qty.value=data.row.qty;f.elements.reason.value='';$('edit-dialog').showModal();}catch(e){notice(e.message,true);}}
function busy(form,value){form.querySelectorAll('button').forEach(b=>b.disabled=value);}
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

// Shared presentation only. Variant APIs remain authoritative for all counts and writes.
const mobileMenu=window.matchMedia('(max-width:800px)');
function setMenu(open){$('main-menu').hidden=!open;$('menu-toggle').setAttribute('aria-expanded',String(open));}
setMenu(!mobileMenu.matches);$('menu-toggle').addEventListener('click',()=>setMenu($('main-menu').hidden));
mobileMenu.addEventListener('change',e=>setMenu(!e.matches));
window.addEventListener('keydown',e=>{if(e.key==='Escape'&&mobileMenu.matches)setMenu(false);});
// Camera-first capture: no editable fields, selection boxes or automatic inventory writes.
let scanner=null,cameraGeneration=0,cameraOpening=false,scanBusy=false,scanReady=false,lastScan='',lastScanAt=0;
const scanMode=cfg.base.includes('PITS-TAGS')?'tag':cfg.base.includes('PITS-BATCH')?'batch':'hu';
let scanValues={},scanStep=0,scanInspection=null;
const scanSteps=scanMode==='tag'?['location','tag','itemcode','qty']:['location',scanMode];
const scanLabels={location:'la locación',hu:'el HU',batch:'el BATCH',tag:'el marbete',itemcode:'el número de parte',qty:'la cantidad'};
function scanPrompt(){if(!$('scan-instruction'))return;$('scan-instruction').textContent='Escanea '+scanLabels[scanSteps[scanStep]];$('scan-progress').textContent=Object.entries(scanValues).map(([k,v])=>({location:'Locación',hu:'HU',batch:'BATCH',tag:'TAG',itemcode:'Parte',qty:'Cantidad'}[k])+': '+v).join(' · ');}
async function stopCamera(){cameraGeneration++;scanReady=false;if(scanner){const reader=scanner;scanner=null;try{await reader.stop();}catch(e){}try{reader.clear();}catch(e){}}}
async function startCamera(){
 if(!$('camera-reader')||cameraOpening||scanner||document.hidden||$('scan-confirm')?.open)return;
 cameraOpening=true;const generation=++cameraGeneration;$('camera-retry').hidden=true;
 try{
  if(!navigator.mediaDevices?.getUserMedia)throw new Error('Cámara no disponible. Abre esta página con HTTPS.');
  if(!window.Html5Qrcode)throw new Error('No se pudo cargar el escáner. Recarga la página.');
  const reader=new Html5Qrcode('camera-reader',{verbose:false});scanner=reader;
  const options={fps:10,disableFlip:true,videoConstraints:{facingMode:{ideal:'environment'}}};
  const success=text=>{if(generation===cameraGeneration&&scanReady)processScan(text);};
  try{await reader.start({facingMode:'environment'},options,success,()=>{});}
  catch(e){if(!/OverconstrainedError|NotFoundError|DevicesNotFoundError/.test(e.name||String(e)))throw e;await reader.start({facingMode:'environment'},{...options,videoConstraints:{}},success,()=>{});}
  if(generation!==cameraGeneration){try{await reader.stop();reader.clear();}catch(e){}if(!document.hidden)setTimeout(startCamera,0);return;}
  scanReady=true;$('camera-status').textContent='Apunta al código con la cámara trasera.';scanPrompt();
 }catch(e){await stopCamera();$('camera-status').textContent=/NotAllowed|Permission|denied/i.test(String(e))?'Permiso de cámara denegado. Habilítalo en el navegador y vuelve a intentar.':(e.message||'No se pudo abrir la cámara.');$('camera-retry').hidden=false;}
 finally{cameraOpening=false;}
}
function parseScan(text,key){
 const parts=text.split('|');if(parts.length===1)return text;
 if(key==='hu'&&parts.length===4&&parts[2].startsWith('EXIDV'))return parts[2].slice(5);
 if(key==='batch'&&parts.length===6&&parts[5].startsWith('CHARG'))return parts[5].slice(5);
 if(key==='itemcode'&&parts.length===6&&parts[2].startsWith('MATNR'))return parts[2].slice(5);
 throw new Error('Este código no corresponde a '+scanLabels[key]+'.');
}
async function processScan(raw){
 if(scanBusy||$('scan-confirm').open)return;const text=String(raw).trim();if(!text)return;
 if(text===lastScan&&Date.now()-lastScanAt<1500)return;lastScan=text;lastScanAt=Date.now();scanBusy=true;
 try{
  const key=scanSteps[scanStep],value=parseScan(text,key);
  if(key==='location')await api('checklocation',{location:value});
  else if(key===scanMode){const data=await api('lookup',{[scanMode]:value});if(data.capture||data.rows.every(r=>r.Status==='Used'))throw new Error('Este '+scanMode.toUpperCase()+' ya fue capturado.');scanInspection=data;}
  else if(key==='qty'&&(!Number.isFinite(Number(value))||Number(value)<=0||Number(value)>1e14))throw new Error('Escanea una cantidad válida mayor que cero.');
  let compoundQty=null;if(key==='itemcode'&&text.split('|').length===6){compoundQty=text.split('|')[3].replace(/^PAQTY/,'');if(!Number.isFinite(Number(compoundQty))||Number(compoundQty)<=0||Number(compoundQty)>1e14)throw new Error('Cantidad inválida en etiqueta.');}
  scanValues[key]=value;scanStep++;if(compoundQty!==null){scanValues.qty=compoundQty;scanStep++;}
  if(scanStep<scanSteps.length){scanPrompt();$('camera-status').textContent='Código leído. Continúa con '+scanLabels[scanSteps[scanStep]]+'.';}
  else{await stopCamera();$('scan-review').textContent=Object.entries(scanValues).map(([k,v])=>({location:'Locación',hu:'HU',batch:'BATCH',tag:'TAG',itemcode:'Parte',qty:'Cantidad'}[k])+': '+v).join('\n')+'\n'+(scanMode==='tag'?'':scanInspection.rows.length+' partidas');$('scan-confirm').showModal();}
 }catch(e){notice(e.message,true);$('camera-status').textContent=e.message;}
 finally{scanBusy=false;}
}
function resetScan(){scanValues={};scanStep=0;scanInspection=null;lastScan='';scanPrompt();}
$('camera-retry')?.addEventListener('click',startCamera);
$('scan-restart')?.addEventListener('click',()=>{if(scanBusy)return;resetScan();startCamera();});
$('scan-cancel')?.addEventListener('click',()=>{$('scan-confirm').close();resetScan();startCamera();});
$('scan-save')?.addEventListener('click',async()=>{
 if(scanBusy)return;scanBusy=true;$('scan-save').disabled=true;$('scan-cancel').disabled=true;
 try{const r=await api('capture',scanValues);$('scan-confirm').close();notice(`${r.message} ${r.partidas} partidas · Ref. ${r.correlation}`);resetScan();}
 catch(e){$('scan-confirm').close();notice(e.message,true);resetScan();$('camera-status').textContent='Escanea nuevamente; el servidor verificará el estado antes de permitir una captura.';}
 finally{scanBusy=false;$('scan-save').disabled=false;$('scan-cancel').disabled=false;startCamera();}
});
$('scan-confirm')?.addEventListener('cancel',e=>{e.preventDefault();if(!scanBusy)$('scan-cancel').click();});
window.addEventListener('pagehide',stopCamera);
document.addEventListener('visibilitychange',()=>{if(document.hidden)stopCamera();else startCamera();});
// Preserve external barcode readers without exposing an additional input field.
let scanBuffer='',keyAt=0;document.addEventListener('keydown',e=>{if(cfg.page!=='capture'||e.ctrlKey||e.metaKey||e.altKey||$('scan-confirm')?.open||e.target.closest('input,textarea,select'))return;if(e.key==='Enter'){e.preventDefault();const value=scanBuffer;scanBuffer='';processScan(value);}else if(e.key.length===1){if(Date.now()-keyAt>150)scanBuffer='';scanBuffer+=e.key;keyAt=Date.now();}});
if(cfg.page==='capture'){scanPrompt();startCamera();}

function el(tag,text,cls){const n=document.createElement(tag);if(text!==undefined)n.textContent=text;if(cls)n.className=cls;return n;}
const money=v=>'$'+Math.ceil(Number(v||0)).toLocaleString('en-US');
const integer=v=>Math.ceil(Number(v||0)).toLocaleString('en-US');
let variationPage=0,variationSize=25,variationSort='Item Code',variationAscending=true;
function reportActions(container){const bar=el('div',undefined,'report-actions');const excel=el('a','Excel','button secondary small');excel.href=cfg.base+'export/'+cfg.page;const print=el('button','Print','secondary small');print.type='button';print.onclick=()=>window.print();bar.append(excel,print);container.append(bar);return bar;}
function renderVariation(rows){
 const container=$('report');container.replaceChildren();const actions=reportActions(container);const select=el('select');select.setAttribute('aria-label','Filas por página');for(const count of [10,25,50,-1]){const option=el('option',count===-1?'Mostrar todos':count+' filas');option.value=count;option.selected=count===variationSize;select.append(option);}select.onchange=()=>{variationSize=Number(select.value);variationPage=0;renderReport();};actions.append(select);
 const wrap=el('div',undefined,'table-wrap');const t=el('table',undefined,'reference-variation');t.id='variationtable';const head=el('thead');const totals=report.totals;
 const top=el('tr');const pc=Number(totals['On Hand USD'])?Math.min(100,Number(totals['Counted USD'])/Number(totals['On Hand USD'])*100):0;
 const progress=el('th','Progress: '+pc.toFixed(2)+'%','variation-progress');progress.colSpan=2;top.append(progress);
 for(const [label,key,cls,span]of [['On hand','On Hand USD','onhand',3],['Counted','Counted USD','counted',2],['Variation','Variation USD','variation',2]]){const h=el('th',label,cls);h.colSpan=span;const amount=el('th',money(totals[key]),cls+' number'+(key==='Variation USD'&&Number(totals[key])<0?' negative':''));top.append(h,amount);}head.append(top);
 const columns=[['Item Code','Item code'],['Type','Type'],['Description','Description'],['On Hand','Quantity'],['Unit Price USD','Unit price USD'],['On Hand USD','Extended USD'],['Counted','Quantity'],['Unit Price USD','Unit price USD'],['Counted USD','Extended USD'],['Variation','Quantity'],['Unit Price USD','Unit price USD'],['Variation USD','Extended USD']];
 const titles=el('tr');columns.forEach(([key,label])=>{const th=el('th');const sort=el('button',label,'column-sort');sort.onclick=()=>{variationAscending=variationSort===key?!variationAscending:true;variationSort=key;renderReport();};th.append(sort);titles.append(th);});head.append(titles);t.append(head);
 const sorted=[...rows].sort((a,b)=>{const av=a[variationSort]??'',bv=b[variationSort]??'';return (report.numeric.includes(variationSort)?Number(av)-Number(bv):String(av).localeCompare(String(bv)))*(variationAscending?1:-1);});
 const pages=variationSize===-1?1:Math.max(1,Math.ceil(rows.length/variationSize));variationPage=Math.min(variationPage,pages-1);const visible=variationSize===-1?sorted:sorted.slice(variationPage*variationSize,(variationPage+1)*variationSize);
 const body=el('tbody');visible.forEach(row=>{const tr=el('tr');columns.forEach(([k],i)=>{const value=k==='Type'?(row.Type||''):row[k];const td=el('td',i<3?String(value??''):(k.endsWith('USD')?money(value):integer(value)),i>=3?'number':'');if(k==='Variation USD'&&Number(value)<0)td.classList.add('negative');if(i===0){const b=el('button',String(value),'item-detail');b.onclick=()=>showItem(row['Item Code']);td.replaceChildren(b);}tr.append(td);});body.append(tr);});t.append(body);
 const foot=el('tfoot'),tr=el('tr'),label=el('th','Totals');label.colSpan=3;tr.append(label);for(let i=3;i<columns.length;i++){const k=columns[i][0];const td=el('th',k==='Unit Price USD'?'':(k.endsWith('USD')?money(totals[k]):integer(totals[k])),'number');if(k==='On Hand'||k==='Counted')td.classList.add('quantity-total');if(k==='On Hand USD'||k==='Counted USD')td.classList.add('amount-total');if(k==='Variation USD')td.classList.add(Number(totals[k])<0?'negative':'variation');tr.append(td);}foot.append(tr);t.append(foot);wrap.append(t);container.append(wrap);
 const pager=el('div',undefined,'report-actions');pager.append(el('span',`${rows.length} registros · Página ${variationPage+1} de ${pages}`));for(const [text,delta]of [['Anterior',-1],['Siguiente',1]]){const b=el('button',text,'secondary small');b.disabled=delta<0?variationPage===0:variationPage>=pages-1;b.onclick=()=>{variationPage+=delta;renderReport();};pager.append(b);}container.append(pager);
}
async function showItem(code){try{const data=await api('report/total');showDetails('Detail data · '+code,data.rows.filter(r=>r['Item Code']===code));}catch(e){notice(e.message,true);}}
function showDetails(title,rows){const dialog=el('dialog',undefined,'report-detail');const close=el('button','Cerrar','secondary');close.onclick=()=>dialog.close();const target=el('div');dialog.append(el('h2',title),close,target);document.body.append(dialog);table(rows,target);dialog.addEventListener('close',()=>dialog.remove());dialog.showModal();}
let schedule=null,progressWarehouse='';
function timeNumber(value){const [h,m]=value.split(':').map(Number);return h+m/60;}
function scheduleRange(){const s=timeNumber(schedule.starttime),e=timeNumber(schedule.endtime);let range=[s];for(let h=Math.floor(s)+1;h<e;h++){if(String(schedule.break)==='1'&&h>timeNumber(schedule.startbreak)&&h<=timeNumber(schedule.endbreak))continue;range.push(h);}range.push(e);return range;}
function renderProgress(rows){
 if(!schedule){api('schedule').then(data=>{schedule=data;renderReport();}).catch(e=>notice(e.message,true));return;}
 const container=$('report');container.replaceChildren();reportActions(container);const wrap=el('div',undefined,'table-wrap progress-wrap');const t=el('table',undefined,'reference-progress');const range=scheduleRange(),n=range.length-1,head=el('thead');
 const timeRow=el('tr'),changeCell=el('th'),change=el('button','Change schedule','secondary small');change.disabled=cfg.role!==3;change.onclick=editSchedule;changeCell.append(change);timeRow.append(changeCell);range.forEach(h=>timeRow.append(el('th',String(Math.floor(h)).padStart(2,'0')+':'+String(Math.round(h%1*60)).padStart(2,'0')+'hrs')));head.append(timeRow);
 const selected=rows.filter(r=>!progressWarehouse||r.WAREHOUSE===progressWarehouse),unitKey=Object.keys(report.totals).find(k=>['HUs','TAGs','BATCHes','Batches','BATCHs'].includes(k))||Object.keys(report.totals).find(k=>!['WAREHOUSE','AREA','Area Description','Captured','Pending'].includes(k));
 const total=selected.reduce((s,r)=>s+Number(r[unitKey]),0),captured=selected.reduce((s,r)=>s+Number(r.Captured),0);
 const clockRow=el('tr');clockRow.append(el('th','Progress: '+(total?captured/total*100:0).toFixed(2)+'%','variation-progress'),el('th','Current time'));const track=el('th',undefined,'time-track');track.colSpan=n;const clock=el('div',undefined,'bartime');clock.id='progress-clock';track.append(clock);clockRow.append(track);head.append(clockRow);
 const scaleRow=el('tr'),filter=el('th');filter.colSpan=2;const select=el('select');select.setAttribute('aria-label','Almacén');const all=el('option','All racks');all.value='';select.append(all);for(const wh of [...new Set(report.rows.map(r=>r.WAREHOUSE))]){const op=el('option',wh);op.value=wh;select.append(op);}select.value=progressWarehouse;select.onchange=()=>{progressWarehouse=select.value;renderReport();};filter.append(select);scaleRow.append(filter);for(let i=1;i<=n;i++)scaleRow.append(el('th',(i/n*100).toFixed(2)+'%','progress-scale'));head.append(scaleRow);t.append(head);
 const body=el('tbody');selected.sort((a,b)=>Number(a.Captured)/Number(a[unitKey])-Number(b.Captured)/Number(b[unitKey])).forEach(row=>{const tr=el('tr');const area=el('td',row.WAREHOUSE+' / '+row.AREA+(row['Area Description']?' · '+row['Area Description']:''));area.title=row.WAREHOUSE;const count=el('td'),link=el('button',row.Captured+'/'+row[unitKey],'item-detail');link.onclick=()=>showDetails(row.AREA,report.units.filter(u=>u.WAREHOUSE===row.WAREHOUSE&&u.AREA===row.AREA&&u['Area Description']===row['Area Description']));count.append(link);const cell=el('td',undefined,'progress-track');cell.colSpan=n;const pc=Number(row[unitKey])?Number(row.Captured)/Number(row[unitKey])*100:0;const bar=el('div',pc.toFixed(2)+'%','bar');bar.style.width=pc+'%';cell.append(bar,el('div',undefined,'time-marker'));tr.append(area,count,cell);body.append(tr);});t.append(body);
 const foot=el('tfoot'),tr=el('tr');tr.append(el('th','Totals'),el('th',captured+'/'+total,'amount-total'));const blank=el('th');blank.colSpan=n;tr.append(blank);foot.append(tr);t.append(foot);wrap.append(t);container.append(wrap);updateClock();
}
function updateClock(){if(!$('progress-clock')||!schedule)return;const range=scheduleRange(),now=new Date(),hour=now.getHours()+now.getMinutes()/60+now.getSeconds()/3600;let fraction=hour<=range[0]?0:hour>=range.at(-1)?1:0;for(let i=0;i<range.length-1;i++){if(hour>=range[i]&&hour<range[i+1])fraction=(i+(hour-range[i])/(range[i+1]-range[i]))/(range.length-1);}const pc=Math.max(0,Math.min(100,fraction*100));$('progress-clock').textContent=now.toLocaleTimeString('en-GB');$('progress-clock').style.width=pc+'%';document.querySelectorAll('.time-marker').forEach(e=>e.style.left=pc+'%');}
setInterval(updateClock,1000);if(cfg.page==='progress')setInterval(loadReport,60000);
function editSchedule(){const dialog=el('dialog'),form=el('form');form.append(el('h2','Change schedule'));for(const [key,title]of [['starttime','Start'],['endtime','End'],['startbreak','Start break'],['endbreak','End break']]){const label=el('label',title),input=el('input');input.type='time';input.name=key;input.required=true;input.value=schedule[key];label.append(input);form.append(label);}const label=el('label','Break time?'),input=el('input');input.type='checkbox';input.name='break';input.checked=String(schedule.break)==='1';label.append(input);form.append(label);const save=el('button','Guardar'),cancel=el('button','Cancelar','secondary');cancel.type='button';cancel.onclick=()=>dialog.close();form.append(save,cancel);form.onsubmit=async e=>{e.preventDefault();save.disabled=true;try{const data=new FormData(form);data.set('break',input.checked?'1':'0');schedule=await api('schedule',data);dialog.close();renderReport();}catch(e){notice(e.message,true);}finally{save.disabled=false;}};dialog.append(form);document.body.append(dialog);dialog.addEventListener('close',()=>dialog.remove());dialog.showModal();}
