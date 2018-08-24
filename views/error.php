<?php $this->layout('layout', ['title' => 'Monocle']) ?>

<h2>Error</h2>

<p>There was a problem trying to load the channels from your Microsub endpoint.</p>

<ul>
  <li>Microsub endpoint: <code><?= e($_SESSION['microsub']) ?></code></li>
  <li>Your website: <code><?= e($_SESSION['token']['me']) ?></code></li>
</ul>

<p>The endpoint returned the following response.</p>

<pre style="white-space: pre-wrap;"><?= e(json_encode($response, JSON_PRETTY_PRINT+JSON_UNESCAPED_SLASHES)) ?></pre>

<p><a href="/logout">Start Over</a></p>
