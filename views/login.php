<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Log In</title>

    <link href="/assets/bulma-0.6.2/css/bulma.css" rel="stylesheet">
    <link href="/assets/styles.css" rel="stylesheet">

    <?php include('views/components/favicon.php') ?>
</head>
<body>

    <section class="hero is-light is-fullheight">

    <div class="hero-body">
        <div class="container has-text-centered">
            <div class="column is-6 is-offset-3">
                <h3 class="title has-text-grey">Sign in to Monocle</h3>
                <div class="box">

                    <?php if($error=p3k\flash('auth_error')): ?>
                      <div class="notification is-danger">
                        <strong><?= e($error) ?></strong>
                        <p><?= e(p3k\flash('auth_error_description')) ?></p>
                      </div>
                    <?php endif; ?>

                    <form method="POST" action="/login">

                        <div class="field">
                            <div class="control">
                                <input id="url" type="url" class="input is-large" name="url" value="<?= $_SESSION['auth_url'] ?? '' ?>" required autofocus placeholder="https://example.com">
                            </div>
                        </div>

                        <button type="submit" class="button is-primary">Log In</button>
                    </form>

                </div>

                <p class="info" style="color: #888;">In order to sign in to Monocle, you'll need a <a href="https://indieweb.org/Microsub">Microsub</a> server.</p>
            </div>
        </div>
    </div>

    </section>

<script>
  /* add http:// to URL fields on blur or when enter is pressed */
  document.addEventListener('DOMContentLoaded', function() {
    function addDefaultScheme(target) {
      if(target.value.match(/^(?!https?:).+\..+/)) {
        target.value = "http://"+target.value;
      }
    }
    var elements = document.querySelectorAll("input[type=url]");
    Array.prototype.forEach.call(elements, function(el, i){
      el.addEventListener("blur", function(e){
        addDefaultScheme(e.target);
      });
      el.addEventListener("keydown", function(e){
        if(e.keyCode == 13) {
          addDefaultScheme(e.target);
        }
      });
    });
  });
</script>
</body>
</html>
