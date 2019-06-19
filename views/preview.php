<?php $this->layout('layout', ['title' => $title]) ?>

<link rel="stylesheet" href="/assets/timeline-styles.css">
<link rel="stylesheet" href="/assets/public-timeline.css">

<?php if(!isset($_GET['url'])): ?>
  <div class="column">
    <h1 class="title">Preview Your Feed</h1>

    <div class="section url-form">
      <form class="" action="" method="get">
        <div class="field"><input type="url" name="url" class="input" placeholder="https://example.com/"></div>
        <div class="field"><button type="submit" class="button is-primary is-fullwidth" value="Go">Go</button></div>
      </form>
    </div>
  </div>
<?php else: ?>
  <h1 class="title">Feed Preview</h1>
  <h2 class="subtitle"><a href="<?= e($_GET['url']) ?>"><?= e(p3k\url\display_url($_GET['url'])) ?></a></h2>
  <div id="window" class="column"></div>
<?php endif ?>


<script>
$(function(){

  var query = parseQueryString(window.location.search.substring(1));

  if(query && query.url) {

    $("#window").html('<progress class="progress is-small is-primary" max="100"></progress>');

    $.post("/preview", {
      url: query.url
    }, function(response){
      $("#window").html(response);
    });

  }

});
</script>
<style type="text/css">
  h1 {
    padding-top: 6rem;
    padding-bottom: 1rem;
    text-align: center;
  }

  h2.subtitle {
    text-align: center;
  }

  a {
    color: #4183c4;
    text-decoration: none;
  }

  .section {
    border: 1px #ccc solid;
    border-radius: 6px;
    background: white;
    padding: 12px;
    margin-top: 2em;
  }
  .help {
    text-align: center;
    font-size: 0.9rem;
  }
</style>
