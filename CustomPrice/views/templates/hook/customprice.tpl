<!-- Block mymodule -->
<div id="custom-price">
  <h4>Precios por caracter:</h4>
  <div class="block_content">
    Cada caracter del texto:
       {if isset($precio_carac_nombre) && $precio_carac_nombre}
           {$precio_carac_nombre}€
       {else}
           Ez dago!
       {/if}
    <br>
    Cada caracter del numero:
       {if isset($precio_carac_num) && $precio_carac_num}
           {$precio_carac_num}€
       {else}
           Ez dago!
       {/if}
    <br>
    Maximo de caracteres para texto:
       {if isset($carac_max) && $carac_max}
           <span class="textMax">{$carac_max} </span> Caracteres
       {else}
           Ez dago!
       {/if}
    <br>
    Maximo de numeros:
       {if isset($num_max) && $num_max}
           <span class="numMax">{$num_max} </span> Numeros
       {else}
           Ez dago!
       {/if}

  </div>
</div>
<!-- /Block mymodule -->