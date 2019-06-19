<div class="error">

  <?php if(isset($result['error'])): ?>

    <article class="message is-danger">
      <div class="message-header">Error: <?= e($result['error']) ?></div>
      <div class="message-body">
        <p><?= e($result['error_description']) ?></p>
        <?php if($result['code']): ?>
          <p>HTTP status code: <?= e($result['code']) ?></p>
        <?php endif ?>
      </div>
    </article>

  <?php else: ?>
    <pre><?php
      print_r($result);
    ?></pre>
  <?php endif ?>

</div>
