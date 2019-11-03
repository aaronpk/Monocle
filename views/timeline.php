<?php $this->layout('layout', ['title' => $channel['name']]) ?>

<link rel="stylesheet" href="/assets/timeline-styles.css">

<div id="window">

  <input type="checkbox" id="nav-trigger" class="nav-trigger" />

  <div id="main-top">
    <label for="nav-trigger" id="nav-trigger-label"></label>
    <h2 class="title is-hidden-mobile">
      <? if($source): ?>
        <a href="/channel/<?= e($channel['uid']) ?>"><?= e($channel['name']) ?></a>
      <? else: ?>
        <?= e($channel['name']) ?>
      <? endif ?>

    </h2>

    <div class="dropdown is-right is-hidden-mobile">
      <div class="dropdown-trigger">
        <a href="#" class="button" aria-haspopup="true" aria-controls="dropdown-menu">
        <span class="icon is-small">
          <?= fa('check') ?>
        </span>
        <span class="icon is-small">
          <?= fa('angle-down') ?>
        </span>
        </a>
      </div>
      <div class="dropdown-menu" id="dropdown-menu" role="menu">
        <div class="dropdown-content">
          <a href="#" class="dropdown-item mark-all-read-button">
            Mark All Read
          </a>
          <a href="/channel/<?= e($channel['uid']) ?>?unread" class="dropdown-item show-unread-button">
            Show Only Unread Entries
          </a>
        </div>
      </div>
    </div>

    <!-- Combo Menu/Title for mobile -->
    <h2 class="title title-combo is-hidden-tablet">
      <div class="dropdown">
        <div class="dropdown-trigger">
          <button class="button" aria-haspopup="true" aria-controls="dropdown-menu">
            <?= e($channel['name']) ?>
            <span class="icon is-small">
              <?= fa('angle-down') ?>
            </span>
          </button>
        </div>
        <div class="dropdown-menu" id="dropdown-menu" role="menu">
          <div class="dropdown-content">
            <a href="#" class="dropdown-item mark-all-read-button">
              <span class="icon is-small">
                <?= fa('check') ?>
              </span> Mark All Read
            </a>
            <a href="/channel/<?= e($channel['uid']) ?>?unread" class="dropdown-item show-unread-button">
              <span class="icon is-small">
                <?= fa('circle') ?>
              </span> Show Only Unread Entries
            </a>
          </div>
        </div>
      </div>
    </h2>
  </div>


  <nav id="side-menu">
    <ul class="channels channel-list">
      <?= $this->insert('components/channel-list', ['selected' => $channel['uid']]) ?>
    </ul>
  </nav>

  <div id="main-container">

    <div class="column"><div class="inner">

      <? if($source): ?>
        <h3 class="source-name">
          <a href="/channel/<?= e($channel['uid']) ?>"><?= fa('arrow-circle-left') ?></a>
          <span style="word-break: break-all;"><?= e($source['name']) ?></span>
        </h3>
      <? endif ?>

      <? foreach($entries as $i=>$entry): ?>
        <div class="entry h-entry <?= isset($entry['like-of']) ? 'like-of' : '' ?> <?= isset($entry['_is_read']) && $entry['_is_read'] == 0 ? 'unread' : 'read' ?>" data-entry="<?= $i ?>" data-entry-id="<?= e($entry['_id']) ?>"
          data-is-read="<?= isset($entry['_is_read']) ? ($entry['_is_read'] ? 1 : 0) : 1 ?>">

          <? if(isset($entry['like-of'])): ?>

            <? /* TODO: if expanded content of the like-of is available, render that post instead */ ?>
            <?= $this->insert('timeline/compact-like', ['entry' => $entry]) ?>
            <?= $this->insert('timeline/actions', ['entry' => $entry, 'responses_enabled' => $responses_enabled]) ?>
            <?= $this->insert('timeline/meta', ['entry' => $entry]) ?>

          <? else: ?>

            <? if(isset($entry['repost-of'])): ?>

              <div class="repost context">
                <a href="<?= e($entry['repost-of'][0]) ?>"><?= fa('retweet') ?></a>
                <? if(!empty($entry['author']['url'])): ?>
                  <a href="<?= e($entry['author']['url']) ?>" class="u-url p-name">
                    <?= e($entry['author']['name'] ?? \p3k\url\display_url($entry['author']['url'])) ?>
                  </a>
                <? elseif(!empty($entry['author']['name'])): ?>
                  <?= e($entry['author']['name']) ?>
                <? else: ?>
                  someone
                <? endif ?>
                reposted
              </div>

              <?
                /* overwrite $entry so that the reposted post is displayed instead */
                if(isset($entry['refs'][$entry['repost-of'][0]])) {
                  $_id = $entry['_id'];
                  $entry = $entry['refs'][$entry['repost-of'][0]];
                  $entry['_id'] = $_id;
                }
              ?>

            <? endif ?>

            <?= $this->insert('timeline/reply-context', ['entry' => $entry]) ?>

            <?= $this->insert('timeline/author-card', ['entry' => $entry, 'channel' => $channel]) ?>

            <? /* ************************************************ */ ?>
            <? /* POST CONTENTS                                    */ ?>

            <?= $this->insert('timeline/checkin', ['entry' => $entry]) ?>

            <?= $this->insert('timeline/name-and-content', ['entry' => $entry]) ?>

            <?= $this->insert('timeline/audio', ['entry' => $entry]) ?>

            <?= $this->insert('timeline/photo-and-video', ['entry' => $entry]) ?>

            <?= $this->insert('timeline/quotation', ['entry' => $entry]) ?>

            <? /* ************************************************ */ ?>

            <?= $this->insert('timeline/meta', ['entry' => $entry]) ?>

            <?= $this->insert('timeline/actions', ['entry' => $entry, 'responses_enabled' => $responses_enabled]) ?>
          <? endif ?>

          <pre style="display: none;" class="source"><?= j($entry) ?></pre>
        </div>
      <? endforeach ?>

      <? if(isset($paging['after'])): ?>
      <nav class="pagination" role="navigation" aria-label="pagination">
        <a class="pagination-next" href="?after=<?= e($paging['after']) ?><?= $show_unread ? '&unread' : '' ?>">More</a>
      </nav>
      <? endif ?>

    </div></div>

    <? if($responses_enabled): ?>
      <div id="main-bottom">

          <div class="new-post-button">
            <a href="#"><span class="icon"><?= fa('pen-square') ?></span></a>
          </div>

          <? if(isset($_SESSION['micropub']['config']['destination'])): ?>
          <div class="dropdown is-up is-right" id="destination-chooser">
            <div class="dropdown-trigger is-right is-mobile">
              <div class="selected-destination">
                <? if(isset($destination['user']['photo'])): ?>
                  <img src="<?= e(image_proxy($destination['user']['photo'], '80x80')) ?>" width="40">
                <? else: ?>
                  <img src="/assets/no-profile-photo.png" width="40">
                <? endif ?>
              </div>
            </div>
            <div class="dropdown-menu">
              <div class="dropdown-content">
                <? foreach($_SESSION['micropub']['config']['destination'] as $dest): ?>
                  <?= $this->insert('components/destination-card', ['destination'=>$dest, 'selected' => $dest['uid']==$destination['uid'], 'tag'=>'a', 'href'=>'#']) ?>
                <? endforeach; ?>
              </div>
            </div>
          </div>
          <? endif ?>

      </div>
    <? endif ?>

  </div>
</div>


<div class="modal" id="new-post-modal">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title"><img src="" width="40"> <span>New Post</span></p>
      <button class="delete" aria-label="close"></button>
    </header>
    <section class="modal-card-body">

      <textarea class="textarea" rows="3" id="new-post-content"></textarea>

    </section>
    <footer class="modal-card-foot">
      <a href="#" class="button post is-primary">Post</a>
      <span class="counter"></span>
    </footer>
  </div>
</div>


<div class="modal" id="source-modal">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title"><span>Source JSON</span></p>
      <button class="delete" aria-label="close"></button>
    </header>
    <section class="modal-card-body">
      <pre></pre>
    </section>
  </div>
</div>

<input type="hidden" id="last-id" value="<?= $entries[0]['_id'] ?? '' ?>">
<input type="hidden" id="channel-uid" value="<?= $channel['uid'] ?>">
<input type="hidden" id="destination-uid" value="<?= e($destination['uid'] ?? '') ?>">

<script>
function addResponseUrl(i, url) {
  $(".entry[data-entry='"+i+"'] .action-responses").append('<div><a href="'+url+'">'+url+'</a></div>');
}

function add_destination(params) {
  if($("#destination-uid").val()) {
    params['mp-destination'] = $("#destination-uid").val();
  }
  return params;
}

$(function(){

  $(window).on('keydown', function(e){
    // Open the new note interface on / unless typing into an input box
    if(e.key == '/') {
      if($("#new-post-modal").hasClass("is-active")) {
        if($("#new-post-content").val() == "" || !$(e.target).is(':input, [contenteditable]')) {
          $("#new-post-modal .delete").click();
          e.preventDefault();
        }
      } else {
        if(!$(e.target).is(':input, [contenteditable]')) {
          $(".new-post-button a").click();
          e.preventDefault();
        }
      }
      return;
    }

    // Open the account chooser on \ unless typing into an input box
    if(!$(e.target).is(':input, [contenteditable]') && (e.key == '\\' || e.keyCode == 13)) {
      // If an account was selected with a keyboard shortcut, actually click it now
      if($("#destination-chooser [data-destination-ch].hover").length > 0) {
        $("#destination-chooser [data-destination-ch].hover").click();
        $("#destination-chooser [data-destination-ch]").removeClass("hover");
      } else {
        if(e.key == '\\') {
          $("#destination-chooser .dropdown-trigger").click();
        }
      }
      e.preventDefault();
      return;
    }

    // If the account chooser is active, then select the account based on the first letter typed
    if($("#destination-chooser").hasClass("is-active")) {
      if(e.keyCode >= 65 && e.keyCode <= 90) { // only for a-z
        var num = $("#destination-chooser [data-destination-ch="+e.key+"]").length;
        if(num == 1) {
          // If only one match, select that one and close
          $("#destination-chooser [data-destination-ch]").removeClass("hover");
          $($("#destination-chooser [data-destination-ch="+e.key+"]")[0]).click();
          e.preventDefault();
          return;
        } else if(num > 1) {
          // If multiple matches, highlight the next one
          var elements = $("#destination-chooser [data-destination-ch="+e.key+"]");
          var selected = $("#destination-chooser [data-destination-ch="+e.key+"].hover");
          if(selected.length == 0) {
            // If none were selected, choose the first
            $("#destination-chooser [data-destination-ch]").removeClass("hover");
            $($("#destination-chooser [data-destination-ch="+e.key+"]")[0]).addClass("hover");
          } else {
            // Find the one that is selected and choose the next (and wrap around)
            var index = 0;
            elements.toArray().forEach(function(el,i){
              if($(selected).data('destination') == $(el).data('destination')) {
                // Mark this as the selected index
                index = i;
              }
              if(i == index+1) {
                // When the iterator gets around to the next element, select it
                $("#destination-chooser [data-destination-ch]").removeClass("hover");
                $(el).addClass("hover");
              } else if(i == elements.length - 1 && $(elements[i]).data('destination') == $(selected).data('destination')) {
                // Wrap around and select the first
                $("#destination-chooser [data-destination-ch]").removeClass("hover");
                $($("#destination-chooser [data-destination-ch="+e.key+"]")[0]).addClass("hover");
              }
            });
          }
        }
      }
    }
  });

  $(".new-post-button a").click(function(){
    $("#new-post-modal .modal-card-title img").attr("src", $(".selected-destination img").attr("src"));
    $("#new-post-content").removeClass('is-danger');
    $('#new-post-modal').addClass('is-active');
    $("#new-post-content").val('').focus();
  });

  $("#new-post-modal a.post").click(function(){
    var btn = $(this);
    btn.addClass('is-loading');
    $.post("/micropub", add_destination({
      "content": $("#new-post-content").val()
    }), function(response){
      btn.removeClass('is-loading');
      if(response.location) {
        $("#new-post-content").removeClass('is-danger');
        $("#new-post-modal").removeClass('is-active');
      } else {
        $("#new-post-content").addClass('is-danger');
      }
    });
  });

  $("a.mark-all-read-button").click(function(e){
    e.preventDefault();
    var marked = {};
    var entry_ids = [];
    document.querySelectorAll(".entry").forEach(function(entry){
      var entryNum = $(entry).data("entry");
      if(marked[entryNum] == null && $(entry).data("is-read") == 0) {
        marked[entryNum] = true;
        entry_ids.push($(entry).data("entry-id"));
      }
    });
    entry_ids.forEach(function(eid){
      $(".entry[data-entry-id="+eid+"]").data("is-read", 1);
      $(".entry[data-entry-id="+eid+"]").removeClass("unread").addClass("read");
    });

    $.post("/microsub/mark_all_as_read", {
      channel: $("#channel-uid").val(),
      entry: $("#last-id").val()
    }, function(response){
      update_channel_list(response.channels);
      $("a.mark-all-read-button").parents(".dropdown").removeClass("is-active");
    });

  });

  $(".content.html").each(function(i,content){
    if($(content).height() >= 384) {
      $(content).find(".read-more").removeClass("hidden");
    }
  });
  $(".read-more a").click(function(e){
    e.preventDefault();
    $(this).parents(".content.html").css('max-height', 'none');
    $(this).parent().remove();
  });

  $(".dropdown-trigger").click(function(){
    $(this).parents(".dropdown").toggleClass("is-active");
  });

  $("#destination-chooser .dropdown-content a").click(function(e){
    e.preventDefault();
    $("#destination-uid").val($(this).data('destination'));
    $("#destination-chooser .selected-destination img").attr("src", $(this).find("img").attr("src"));
    $("#destination-chooser .dropdown-item").removeClass("selected");
    $('#destination-chooser .dropdown-item[data-destination="'+$(this).data('destination')+'"]').addClass("selected");
    $(this).parents(".dropdown").removeClass("is-active");
  });

  $(".actions .action-buttons a").click(function(e){
    e.preventDefault();
    var btn = $(this);

    switch($(this).data("action")) {
      case "favorite":
        btn.addClass("is-loading");
        $.post("/micropub", add_destination({
          "like-of": $(this).parents(".actions").data("url")
        }), function(response){
          btn.removeClass("is-loading");
          if(response.location) {
            addResponseUrl(btn.parents(".entry").data("entry"), response.location);
          }
        });
        break;
      case "repost":
        btn.addClass("is-loading");
        $.post("/micropub", add_destination({
          "repost-of": $(this).parents(".actions").data("url")
        }), function(response){
          btn.removeClass("is-loading");
          if(response.location) {
            addResponseUrl(btn.parents(".entry").data("entry"), response.location);
          }
        });
        break;
      case "reply":
        $(this).parents(".actions").find(".new-reply").removeClass("hidden");
        $(this).parents(".actions").find(".new-reply textarea").focus();
        break;
      case "remove":
        var btn = $(this).parents(".dropdown").find(".dropdown-trigger button");
        btn.addClass("is-loading");

        $.post("/microsub/remove", {
          channel: $("#channel-uid").val(),
          entry: $(this).parents(".entry").data("entry-id")
        }, function(response){
          btn.removeClass("is-loading");
          $('.entry[data-entry-id="'+response.entry+'"]').remove();
        });
        break;
      case "debug":
        $("#source-modal pre").html($(this).parents(".entry").find(".source").html());
        $("#source-modal").addClass("is-active");
        $(this).parents(".dropdown").removeClass("is-active");
        break;
      case "mark-unread":
        e.stopPropagation();
        var btn = $(this).parents(".dropdown").find(".dropdown-trigger button");

        // Slow version, wait for server to confirm
        // btn.addClass("is-loading");
        // mark_unread($(this).parents(".entry").data("entry-id"), function(){
        //   btn.removeClass("is-loading");
        //   btn.parents(".dropdown").removeClass("is-active");
        // });

        // Make it look like it worked immediately
        btn.parents(".dropdown").removeClass("is-active");
        mark_unread($(this).parents(".entry").data("entry-id"));

        break;
      default:
        console.log("Unknown action");
    }
  });

  $(".actions .post-reply").click(function(){
    if($(this).parents(".actions").find(".new-reply textarea").val() == "") {
      return false;
    }

    var btn = $(this);
    btn.addClass("is-loading");
    $.post("/micropub", add_destination({
      "in-reply-to": $(this).parents(".actions").data("url"),
      "content": $(this).parents(".actions").find(".new-reply textarea").val()
    }), function(response){
      btn.removeClass("is-loading");
      if(response.location) {
        btn.parents(".actions").find(".new-reply textarea").val("");
        btn.parents(".actions").find(".new-reply").addClass("hidden");
        btn.removeClass("is-danger");
        addResponseUrl(btn.parents(".entry").data("entry"), response.location);
      } else {
        btn.addClass("is-danger");
      }
    });
  });

  $('.new-reply textarea').on('keyup', function(){
    var len = $(this).val().length;
    $(this).parents('.new-reply').find('.counter').text(len);
  });

  $('#new-post-modal textarea').on('keyup', function(){
    var len = $(this).val().length;
    $('#new-post-modal').find('.counter').text(len);
  });

  $(".entry").click(function(){
    if($(this).data('is-read') == 0) {
      mark_read($(this).data('entry-id'));
    }
  });

});

var last_reload_timestamp = <?= $_SESSION['channels_timestamp'] ?? time() ?>;

setInterval(function(){
  // Every 5 seconds, check how long it's been since the last channel reload,
  // and reload the channels if it's been > 1 minute
  var diff = parseInt(Date.now()/1000) - last_reload_timestamp;
  if(diff > 60) {
    reload_channels();
  }
}, 5000);
reload_channels();

function reload_channels() {
  $.post("/channels/reload?format=json", function(response){
    update_channel_list(response.channels);
  });
}

function update_channel_list(channels) {
  channels.forEach(function(ch){
    last_reload_timestamp = parseInt(Date.now() / 1000);
    if(ch.unread && ch.unread > 0) {
      $('.channels li[data-channel-uid="'+ch.uid+'"] .tag').removeClass('is-hidden').text(typeof ch.unread == "number" ? ch.unread : "");
    } else {
      $('.channels li[data-channel-uid="'+ch.uid+'"] .tag').addClass('is-hidden').text("");
    }
  });
}

function mark_read(entry_ids) {
  if(typeof entry_ids != "object") {
    entry_ids = [entry_ids];
  }

  entry_ids.forEach(function(eid){
    $(".entry[data-entry-id="+eid+"]").data("is-read", 1);
    $(".entry[data-entry-id="+eid+"]").removeClass("unread").addClass("read");
  });

  $.post("/microsub/mark_read", {
    channel: $("#channel-uid").val(),
    entry: entry_ids
  }, function(response){
    update_channel_list(response.channels);
  });
}

function mark_unread(entry_ids, callback=null) {
  if(typeof entry_ids != "object") {
    entry_ids = [entry_ids];
  }

  // Make it look unread even before the server confirms
  entry_ids.forEach(function(eid){
    $(".entry[data-entry-id="+eid+"]").data("is-read", 0);
    $(".entry[data-entry-id="+eid+"]").removeClass("read").addClass("unread");
  });

  $.post("/microsub/mark_unread", {
    channel: $("#channel-uid").val(),
    entry: entry_ids
  }, function(response){
    // Wait until the server confirms before marking as unread
    // entry_ids.forEach(function(eid){
    //   $(".entry[data-entry-id="+eid+"]").data("is-read", 0);
    //   $(".entry[data-entry-id="+eid+"]").removeClass("read").addClass("unread");
    // });
    update_channel_list(response.channels);
    if(callback) {
      callback();
    }
  });
}

/* Mark entries as read when scrolled off the screen */

var marked = {};

$(window).scroll(function() {
  clearTimeout($.data(this, 'scrollTimer'));
  $.data(this, 'scrollTimer', setTimeout(function() {
    var bodyRect = document.body.getBoundingClientRect();
    var contentRect = document.getElementById("main-container").getBoundingClientRect();

    // If you're scrolled to the bottom, mark all as read
    if(-1 * bodyRect.top + bodyRect.height >= contentRect.height - 50) {
      var entryIds = [];
      document.querySelectorAll(".entry").forEach(function(entry){
        var entryNum = $(entry).data("entry");
        if(marked[entryNum] == null && $(entry).data("is-read") == 0) {
          marked[entryNum] = true;
          entryIds.push($(entry).data("entry-id"));
        }
      });
      if(entryIds.length > 0) {
        mark_read(entryIds);
      }
    } else {
      // Find all entries that are scrolled off the page
      var entryIds = [];
      document.querySelectorAll(".entry").forEach(function(entry){
        var bounds = entry.getBoundingClientRect();
        if(bounds.top < 0) {
          var entryNum = $(entry).data("entry");
          if(marked[entryNum] == null && $(entry).data("is-read") == 0) {
            marked[entryNum] = true;
            entryIds.push($(entry).data("entry-id"));
          }
        }
      });
      if(entryIds.length > 0) {
        mark_read(entryIds);
      }
    }
  }, 200));
});

</script>
