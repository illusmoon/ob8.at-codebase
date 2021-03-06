<?php defined("SYSPATH") or die("No direct script access.") ?>
<script type="text/javascript" src="<?= url::file("lib/swfobject.js") ?>"></script>
<script type="text/javascript" src="<?= url::file("lib/uploadify/jquery.uploadify.min.js") ?>"></script>
<script type="text/javascript">
  <? $flash_minimum_version = "9.0.24" ?>
  var success_count = 0;
  var error_count = 0;
  var updating = 0;
  $("#g-add-photos-canvas").ready(function () {
    var update_status = function() {
      if (updating) {
        // poor man's mutex
        setTimeout(function() { update_status(); }, 500);
      }
      updating = 1;
      $.get("<?= url::site("uploader/status/_S/_E") ?>"
            .replace("_S", success_count).replace("_E", error_count),
          function(data) {
            $("#g-add-photos-status-message").html(data);
            updating = 0;
          });
    };

    if (swfobject.hasFlashPlayerVersion("<?= $flash_minimum_version ?>")) {
      $("#g-uploadify").uploadify({
        width: 150,
        height: 33,
        uploader: "<?= url::file("lib/uploadify/uploadify.swf") ?>",
        script: "<?= url::site("uploader/add_photo/{$album->id}") ?>",
        scriptData: <?= json_encode($script_data) ?>,
        fileExt: "*.gif;*.jpg;*.jpeg;*.png;*.GIF;*.JPG;*.JPEG;*.PNG<? if ($movies_allowed): ?>;*.flv;*.mp4;*.m4v;*.FLV;*.MP4;*.M4V<? endif ?>",
        fileDesc: <?= t("Photos and movies")->for_js() ?>,
        cancelImg: "<?= url::file("lib/uploadify/cancel.png") ?>",
        simUploadLimit: <?= $simultaneous_upload_limit ?>,
        wmode: "transparent",
        hideButton: true, /* should be true */
        auto: true,
        multi: true,
        onAllComplete: function(filesUploaded, errors, allbytesLoaded, speed) {
          $("#g-upload-cancel-all")
            .addClass("ui-state-disabled")
            .attr("disabled", "disabled");
          $("#g-upload-done")
            .removeClass("ui-state-disabled")
            .attr("disabled", null);
          return true;
        },
        onClearQueue: function(event) {
          $("#g-upload-cancel-all")
            .addClass("ui-state-disabled")
            .attr("disabled", "disabled");
          $("#g-upload-done")
            .removeClass("ui-state-disabled")
            .attr("disabled", null);
          return true;
        },
        onComplete: function(event, queueID, fileObj, response, data) {
          var re = /^error: (.*)$/i;
          var msg = re.exec(response);
          $("#g-add-photos-status ul").append(
            "<li id=\"q" + queueID + "\" class=\"g-success\">" + fileObj.name + " - " +
            <?= t("Completed")->for_js() ?> + "</li>");
          setTimeout(function() { $("#q" + queueID).slideUp("slow").remove() }, 5000);
          success_count++;
          update_status();
          return true;
        },
        onError: function(event, queueID, fileObj, errorObj) {
          var msg = " - ";
          if (errorObj.type == "HTTP") {
            if (errorObj.info == "500") {
              msg += <?= t("Unable to process this file")->for_js() ?>;
              // Server error - check server logs
            } else if (errorObj.info == "404") {
              msg += <?= t("The upload script was not found.")->for_js() ?>;
              // Server script not found
            } else {
              // Server Error: status: errorObj.info
              msg += (<?= t("Server error: __INFO__")->for_js() ?>.replace("__INFO__", errorObj.info));
            }
          } else if (errorObj.type == "File Size") {
            var sizelimit = $("#g-uploadify").uploadifySettings(sizeLimit);
            msg += fileObj.name+' '+errorObj.type+' Limit: '+Math.round(d.sizeLimit/1024)+'KB';
          } else {
            msg += (<?= t("Server error: __INFO__ (__TYPE__)")->for_js() ?>
              .replace("__INFO__", errorObj.info)
              .replace("__TYPE__", errorObj.type));
          }
          $("#g-add-photos-status ul").append(
            "<li id=\"q" + queueID + "\" class=\"g-error\">" + fileObj.name + msg + "</li>");
          $("#g-uploadify").uploadifyCancel(queueID);
          error_count++;
          update_status();
        },
        onSelect: function(event) {
          if ($("#g-upload-cancel-all").hasClass("ui-state-disabled")) {
            $("#g-upload-cancel-all")
              .removeClass("ui-state-disabled")
              .attr("disabled", null);
            $("#g-upload-done")
              .addClass("ui-state-disabled")
              .attr("disabled", "disabled");
          }
          return true;
        }
      });
    } else {
      $(".requires-flash").hide();
      $(".no-flash").show();
    }
  });
</script>

<div class="requires-flash">
  <? if ($suhosin_session_encrypt || !$movies_allowed): ?>
  <div class="g-message-block g-info">
    <? if ($suhosin_session_encrypt): ?>
    <p class="g-error">
      <?= t("Error: your server is configured to use the <a href=\"%encrypt_url\"><code>suhosin.session.encrypt</code></a> setting from <a href=\"%suhosin_url\">Suhosin</a>.  You must disable this setting to upload photos.",
          array("encrypt_url" => "http://www.hardened-php.net/suhosin/configuration.html#suhosin.session.encrypt",
      "suhosin_url" => "http://www.hardened-php.net/suhosin/")) ?>
    </p>
    <? endif ?>

    <? if (!$movies_allowed): ?>
    <p class="g-warning">
      <?= t("Can't find <i>ffmpeg</i> on your system. Movie uploading disabled. <a href=\"%help_url\">Help!</a>", array("help_url" => "http://codex.gallery2.org/Gallery3:FAQ#Why_does_it_say_I.27m_missing_ffmpeg.3F")) ?>
    </p>
    <? endif ?>
  </div>
  <? endif ?>

  <div>
    <p>
      <?= t("Photos will be uploaded to album: ") ?>
    </p>
    <ul class="g-breadcrumbs ui-helper-clearfix">
      <? foreach ($album->parents() as $i => $parent): ?>
      <li<? if ($i == 0) print " class=\"g-first\"" ?>> <?= html::clean($parent->title) ?> </li>
      <? endforeach ?>
      <li class="g-active"> <?= html::purify($album->title) ?> </li>
    </ul>
  </div>

  <div id="g-add-photos-canvas">
    <button id="g-add-photos-button" class="g-button ui-state-default ui-corner-all" href="#"><?= t("Select photos...") ?></button>
    <span id="g-uploadify"></span>
  </div>
  <div id="g-add-photos-status">
    <ul id="g-action-status" class="g-message-block">
    </ul>
  </div>
</div>

<div class="no-flash" style="display: none">
  <p>
    <?= t("Your browser must have Adobe Flash Player version %flash_minimum_version or greater installed to use this feature.", array("flash_minimum_version" => $flash_minimum_version)) ?>
  </p>
  <a href="http://www.adobe.com/go/getflashplayer">
    <img src="<?= request::protocol() ?>://www.adobe.com/images/shared/download_buttons/get_flash_player.gif"
         alt=<?= t("Get Adobe Flash Player")->for_js() ?> />
  </a>
</div>
