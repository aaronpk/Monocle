<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Monocle</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet" type="text/css">
        <link href="/assets/bulma-0.6.2/css/bulma.css" rel="stylesheet">
        <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>

        <?php include('views/components/favicon.php') ?>

        <!-- Styles -->
        <style>
            .full-height {
                min-height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .top-left {
                position: absolute;
                left: 10px;
                top: 18px;
            }

          .tile .icon {
            float: left;
            margin-right: 40px;
            margin-bottom: 20px;
            margin-left: 10px;
            margin-top: 20px;
          }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #fff;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .cover-photo-main {
              background: url(/images/indieweb-reader.jpg);
              background-position: center;
              background-size: cover;
            }

            .tagline {
              text-align: center;
              font-size: 2em;
              padding: 2em 0;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .signup {
              background: #eee;
            }

            .credits {
              padding: 3em;
            }

        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height cover-photo-main h-x-app h-app">
            <div class="top-right links">
                <a href="/login">Sign In</a>
            </div>

            <div class="top-left links">
                <img src="/icons/monocle-white.png" alt="Monocle Logo" class="u-logo" width="30" style="margin-bottom: -12px; margin-left: 20px;">
                <a class="p-name u-url" href="/">Monocle</a>
            </div>
        </div>

        <section class="section">
          <div class="container">
            <div class="flex-center position-ref full-height">
                <div class="content">

                  <div class="logo" style="text-align: center;">
                    <img src="/icons/monocle.png" alt="Monocle Logo" width="96">
                  </div>

                  <div class="tagline">Monocle is your <a href="https://aaronparecki.com/2018/04/20/46/indieweb-reader-my-new-home-on-the-internet">new home on the Internet</a>.</div>


                  <div class="tile is-ancestor">
                    <div class="tile is-parent">
                      <article class="tile is-child box">
                        <span class="icon">
                          <i class="fas fa-3x fa-search"></i>
                        </span>
                        <p>Monocle is an IndieWeb reader. Read and respond to any kind of content online! Monocle provides an interface to read and reply to posts from anything you follow.</p>
                      </article>
                    </div>
                    <div class="tile is-parent">
                      <article class="tile is-child box">
                        <span class="icon">
                          <i class="fas fa-3x fa-rss-square"></i>
                        </span>
                        <p>Monocle doesn't subscribe to feeds itself, instead it's an interface on top of your own feed subscriptions. Your website may already provide this API, or you can use an external service like <a href="https://aperture.p3k.io">Aperture</a>, or <a href="https://indieweb.org/Microsub#Servers">many others</a>.</p>
                      </article>
                    </div>
                    <div class="tile is-parent">
                      <article class="tile is-child box">
                        <span class="icon">
                          <i class="fas fa-3x fa-mobile"></i>
                        </span>
                        <p>Monocle is just one of many IndieWeb readers! It works great on a mobile device, but if you prefer a native app you can use Indigenous for <a href="https://indieweb.org/Indigenous_for_iOS">iPhone</a> or <a href="https://indieweb.org/Indigenous_for_Android">Android</a>.</p>
                      </article>
                    </div>
                  </div>

                </div>
            </div>
          </div>
        </section>

        <section class="section">
          <div class="container content">
            <h2>Technical Details</h2>

            <p>Monocle is a <a href="https://indieweb.org/Microsub">Microsub</a> client. Microsub is a spec that provides a standardized way for reader apps to interact with feeds. By splitting feed parsing and displaying posts into separate parts, a reader app can focus on presenting posts to the user instead of also having to parse feeds. A Microsub server manages the list of people you're following and collects their posts, and a Micropub app shows the posts to the user by fetching them from the server.</p>
            <p>To use Monocle, you need to have a website which points to a Microsub server so that Monocle knows where to find content to display.</p>
            <p>If your website also supports <a href="https://micropub.net/">Micropub</a>, then you'll be able to favorite and reply to posts from within Monocle too.</p>
            <p>You can read more about the technical details of Monocle at <a href="https://aaronparecki.com/2018/03/12/17/building-an-indieweb-reader">Building an IndieWeb Reader</a>.</p>
          </div>
        </section>

        <footer class="footer">
          <div class="flex-center position-ref credits">
            <div>Monocle is created by <a href="https://aaronparecki.com/">Aaron Parecki</a> and is part of the <a href="https://indieweb.org/">IndieWeb</a>.</div>
          </div>
        </footer>

    </body>
</html>
