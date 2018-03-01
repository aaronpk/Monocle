<?php $this->layout('layout', ['title' => 'Monocle']) ?>

<style>
.channels {
  width: 240px;
  margin: 0 auto;
  line-height: 2.4;
}
.channels .tag {
  float: right;
  margin-top: 7px;
}
.channels a {
  text-decoration: none;
}
</style>

<div class="column">
  <ul class="channels channel-list">
    <?= $this->insert('components/channel-list') ?>
  </ul>
  <ul class="channels">
    <li><br></li>
    <li><a href="#" class="button action-reload">Reload</a></li>
    <li><br></li>
    <li><a href="/logout" class="button">Log Out</a></li>
  </ul>
</div>

<script>
$(function(){

  $(".action-reload").click(function(e){
    e.preventDefault();
    $(this).addClass("is-loading");
    $.post("/channels/reload", function(response){
      $(".channel-list").html(response);
      $(".action-reload").removeClass("is-loading");
    });
  });

});
</script>
