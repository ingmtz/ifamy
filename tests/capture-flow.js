// Exercise the actual view functions and confirmation callbacks with a stubbed DOM/AJAX.
const fs=require('fs'),vm=require('vm'),assert=require('assert');
let checks=0;
for(const mode of ['hu','batch']) {
 const view=fs.readFileSync(`${__dirname}/../application/views/capture_${mode}_view.php`,'utf8');
 const script=view.match(/<script type="text\/javascript">([\s\S]*?)<\/script>/)[1].replace(/<\?[\s\S]*?\?>/g,'');
 const code=script.slice(script.indexOf("\tvar location ="),script.indexOf('\t// ============================================\n\t// INITIALIZE QR CODE SCANNER'));
 let done,fail,confirm;
 const requests=[];
 const chain={modal(){return this},off(){return this},on(){return this},removeClass(){return this},addClass(){return this},html(){return this},val(){return ''}};
 const $=()=>chain;
 $.ajax=(opts)=>{requests.push({...opts.data});return {done(fn){done=fn;return this},fail(fn){fail=fn;return this}}};
 const c={$,console:{log(){},error(){}},correct:{play(){}},wrong:{play(){}},PNotify:function(){}};
 vm.createContext(c);vm.runInContext(code,c);
 c.showOpenBoxConfirm=fn=>confirm=fn;
 function response(complete=false) {done({...requests.at(-1),status:true,isComplete:complete,message:requests.at(-1).openbox===''?'HU correct, capture open box option':'Open box option selected, capture quantity'});}
 c.checkCode('R1');done({...requests.at(-1),status:true,message:'Location correct, capture HU'});
 c.checkCode('HU1');response();
 c.checkCode('IGNORED');assert.equal(requests.length,2);checks++;
 confirm(false);assert.equal(requests.at(-1).openbox,'0');assert.equal(requests.at(-1).quantity,'');checks++;
 c.checkCode('IGNORED');assert.equal(requests.length,3);checks++;
 response(true);
 c.checkCode('R1');done({...requests.at(-1),status:true,message:'Location correct'});
 c.checkCode('HU2');response();confirm(true);
 assert.equal(requests.at(-1).openbox,'1');assert.equal(requests.at(-1).quantity,'');checks++;
 response();c.checkCode('7.5');assert.equal(requests.at(-1).quantity,'7.5');checks++;
 fail({},'error');assert.equal(c.requestPending,false);checks++;
 new vm.Script(script); // Syntax of entire rendered inline script.
}
console.log(`${checks} frontend checks passed`);
