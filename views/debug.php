<?php $this->layout('layout', ['title' => 'Monocle']) ?>

<div class="column">

  <h2 class="title">Endpoints</h2>
  <p>Micropub: <code><?= e($session['micropub']['endpoint']) ?></code></p>
  <p>Microsub: <code><?= e($session['microsub']) ?></code></p>
  <br>

  <h3 class="subtitle">Config</h3>
  <a href="#" class="button reload-config">Reload</a>
  <pre><?= j($session['micropub']['config']) ?></pre>
  <br>

  <h3 class="subtitle">Cached Channels</h3>
  <a href="#" class="button reload-channels">Reload</a>
  <pre><?= j($session['channels']) ?></pre>
  <br>

  <h3 class="subtitle">Token</h3>
  <pre><?= j($session['token']) ?></pre>
  <br>

  <br>
  <a href="/logout" class="button">Log Out</a>

</div>

<script>
$(function(){

  $(".reload-config").click(function(e){
    e.preventDefault();
    $(this).addClass("is-loading");
    $.post("/micropub/refresh", function(response){
      window.location.reload();
    });
  });

  $(".reload-channels").click(function(e){
    e.preventDefault();
    $(this).addClass("is-loading");
    $.post("/channels/reload", function(response){
      window.location.reload();
    });
  });

});
</script>
