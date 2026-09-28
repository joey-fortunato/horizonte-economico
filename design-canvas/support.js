/* Design canvas support shim.
   Os artboards usam elementos <x-dc> e <helmet> só como invólucros semânticos.
   Os browsers renderizam elementos desconhecidos inline e aplicam o <style>
   contido no <helmet>, por isso não é necessária lógica adicional aqui. */
(function(){
  try{ document.documentElement.classList.add('dc-ready'); }catch(e){}
})();
