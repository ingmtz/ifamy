const {chromium}=require('playwright');
const fs=require('fs');
const out=process.env.PITS_TEST_OUTPUT||require('os').tmpdir()+'/pits-batch-web-checks';fs.mkdirSync(out,{recursive:true});
const base=process.env.PITS_BATCH_URL||'https://novarum.app/PITS-BATCH/';
const accounts=JSON.parse(fs.readFileSync(process.env.PITS_TEST_ACCOUNTS||'/root/.openclaw/credentials/accounts/pits-tests-20261121.json')).BATCH.test_accounts;
(async()=>{
const browser=await chromium.launch({headless:true,args:['--no-sandbox']});
const results=[];
try{
 const anon=await browser.newContext();const ar=await anon.request.get(base+'api/report/variation');if(ar.status()!==401)throw Error('Anonymous API not denied');results.push('PASS anonymous API denied');await anon.close();
 for(const account of accounts){
  const ctx=await browser.newContext({viewport:{width:1440,height:1000},acceptDownloads:true});const page=await ctx.newPage();const errors=[];page.on('pageerror',e=>errors.push(e.message));
  await page.goto(base+'login');await page.getByLabel('Usuario',{exact:true}).fill(account.username);await page.getByLabel('Contraseña',{exact:true}).fill(account.password);await page.getByRole('button',{name:'Iniciar sesión'}).click();await page.waitForURL('**/'+(account.idprofile===2?'capture':'reports/variation'));
  await page.waitForLoadState('networkidle');
  const config=await page.locator('#batch-config').textContent().then(JSON.parse);
  const cookies=await ctx.cookies();const session=cookies.find(c=>c.name==='pits_batch_session');if(!session||session.path!=='/PITS-BATCH/'||!session.secure||!session.httpOnly)throw Error('Session not isolated');
  results.push('PASS '+account.profile+' login, correct landing and isolated cookie');
  for(const path of ['capture/checkCode','capture/checkCodeHu','reports/fileupload','capture/checkCodeBatch']){const r=await ctx.request.post(base+path,{form:{[config.csrfName]:config.csrf},headers:{'X-Requested-With':'XMLHttpRequest'}});if(r.status()!==404)throw Error('Legacy endpoint accessible '+path+' '+r.status());}
  results.push('PASS '+account.profile+' legacy endpoints fail closed even with AJAX header');
  const csrf=await ctx.request.post(base+'api/capture',{form:{batch:'INVALID',location:'INVALID'}});if(csrf.status()!==403)throw Error('Missing CSRF not denied');
  if(account.idprofile===2){
   const nav=await page.locator('nav a').allTextContents();if(nav.length!==1||!nav[0].includes('Captura'))throw Error('Capturist menu expanded');
   for(const p of ['report/variation','editable','correct','audit','importfile']){const r=await ctx.request.post(base+'api/'+p,{form:{[config.csrfName]:config.csrf}});if(r.status()!==403)throw Error('Capturist admin endpoint accessible '+p);}
   await page.screenshot({path:out+'/capture-desktop.png',fullPage:true});
   await page.setViewportSize({width:390,height:844});await page.screenshot({path:out+'/capture-mobile.png',fullPage:true});
   results.push('PASS capturist only capture, direct admin API denied, mobile render');
  }else{
   await page.waitForSelector('#report table');
   const r=await ctx.request.get(base+'api/report/variation');const data=(await r.json()).data;if(!data.rows.length)throw Error('Empty copied inventory report');
   const dl=page.waitForEvent('download');await page.getByRole('link',{name:'Exportar XLSX'}).click();const download=await dl;await download.saveAs(out+'/variation-'+account.idprofile+'.xlsx');
   fs.writeFileSync(out+'/variation-'+account.idprofile+'.json',JSON.stringify(data));
   for(const screen of ['progress','total','typeraw']){await page.goto(base+'reports/'+screen);await page.waitForLoadState('networkidle');if(!(await page.locator('#report table').count()))throw Error('Missing report '+screen);if(screen==='progress'){const r=await ctx.request.get(base+'api/report/progress');const data=(await r.json()).data;if(!data.units?.length)throw Error('Missing BATCH progress details');const dl=page.waitForEvent('download');await page.getByRole('link',{name:'Exportar XLSX'}).click();await (await dl).saveAs(out+'/progress-'+account.idprofile+'.xlsx');fs.writeFileSync(out+'/progress-'+account.idprofile+'.json',JSON.stringify(data));}}
   if(account.idprofile===1){const r=await ctx.request.post(base+'api/correct',{form:{[config.csrfName]:config.csrf}});if(r.status()!==403)throw Error('Analyst correction not denied');}
   await page.goto(base+'reports/variation');await page.waitForLoadState('networkidle');await page.screenshot({path:out+'/variation-'+account.idprofile+'.png',fullPage:true});
   results.push('PASS '+account.profile+' reports and XLSX download; correction role enforced');
  }
  if(errors.length)throw Error('Browser JS errors: '+errors.join(';'));
  const logout=await ctx.request.post(base+'logout',{form:{[config.csrfName]:config.csrf}});const denied=await ctx.request.get(base+'api/report/variation');if(denied.status()!==401)throw Error('Logout did not invalidate session');
  results.push('PASS '+account.profile+' logout invalidates session');await ctx.close();
 }
 for(const path of ['application/config/database.php','database/batch-001.sql','tests/batch_integration.php','.git/config']){const r=await browser.newContext().then(async c=>{const r=await c.request.get(base+path);await c.close();return r.status();});if(r!==403)throw Error('Private path not denied '+path+' '+r);}
 results.push('PASS private/config/test paths denied');
 console.log(results.join('\n'));fs.writeFileSync(out+'/browser-results.txt',results.join('\n'));
}finally{await browser.close();}
})().catch(e=>{console.error(e.message);process.exit(1);});
