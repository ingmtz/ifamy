const {chromium}=require('playwright');const fs=require('fs');
(async()=>{const browser=await chromium.launch({args:['--no-sandbox']});try{
const a=JSON.parse(fs.readFileSync('/root/.openclaw/credentials/accounts/pits-tests-20261121.json')).TAGS.test_accounts.find(x=>x.idprofile===2);
for(const scenario of ['unsupported','denied','rear','fallback']){
 const ctx=await browser.newContext();await ctx.addInitScript(scenario=>{
 window.cameraRequests=[];window.stopped=false;
 Object.defineProperty(window,'BarcodeDetector',{configurable:true,value:scenario==='unsupported'?undefined:class {async detect(){return [{rawValue:'00001'}]}}});
 Object.defineProperty(navigator.mediaDevices,'getUserMedia',{configurable:true,value:async args=>{cameraRequests.push(args);if(scenario==='denied')throw new DOMException('Denied','NotAllowedError');if(scenario==='fallback'&&args.video!==true)throw new DOMException('Unavailable','OverconstrainedError');return {getTracks:()=>[{stop:()=>window.stopped=true}]};}});
 Object.defineProperty(HTMLMediaElement.prototype,'srcObject',{configurable:true,set(v){},get(){return null;}});HTMLMediaElement.prototype.play=async()=>{};
 },scenario);
 const p=await ctx.newPage();await p.goto('https://novarum.app/PITS-TAGS/login');await p.getByLabel('Usuario',{exact:true}).fill(a.username);await p.getByLabel('Contraseña',{exact:true}).fill(a.password);await p.getByRole('button',{name:'Iniciar sesión'}).click();await p.waitForURL('**/capture');let mutations=0;p.on('request',r=>{if(r.url().endsWith('api/capture'))mutations++});await p.locator('#camera-start').click();
 if(['rear','fallback'].includes(scenario)){await p.waitForFunction(()=>document.getElementById('capture-tag').value==='00001');const data=await p.evaluate(()=>({requests:cameraRequests,stopped}));if(data.requests[0].video.facingMode.ideal!=='environment'||!data.stopped)throw Error('camera preference/cleanup failed');if(scenario==='fallback'&&data.requests[1].video!==true)throw Error('fallback failed');}
 else await p.waitForFunction(()=>document.getElementById('camera-status').textContent.includes('lector externo'));
 if(mutations)throw Error('Camera saved without confirmation');if(!(await p.locator('#capture-tag').isEditable()))throw Error('Manual entry disabled');console.log('PASS camera '+scenario+'; no inventory mutation, manual entry available');await ctx.close();
}
}finally{await browser.close();}})().catch(e=>{console.error(e);process.exit(1)});
