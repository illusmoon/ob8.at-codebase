<?php

/**
 * Contao Repository :: Template to update multiple extensions
 *
 * @package    Repository
 * @copyright  Peter Koch 2008-2010
 * @author     Peter Koch, IBK Software AG
 * @license    See accompaning file LICENSE.txt
 */
$rep = &$this->rep;
$text = &$GLOBALS['TL_LANG']['tl_repository'];

?>

<div id="tl_buttons">
<a href="<?php echo $rep->homeLink; ?>" class="header_back" title="<?php echo $text['goback']; ?>" accesskey="b" onclick="Backend.getScrollOffset();"><?php echo $text['goback']; ?></a>
</div>

<h2 class="sub_headline"><?php echo $text['installlogtitle']; ?></h2>

<div class="mod_repository block">
<form action="<?php echo $rep->f_link; ?>" id="repository_upgdform" method="post" >
<div class="tl_formbody_edit">
<input type="hidden" name="repository_action" value="<?php echo $rep->f_action; ?>" />

<div class="installlog">
<?php echo $rep->log; ?>
</div>

</div>

<div class="mod_repository_submit tl_formbody_submit">

<div class="tl_submit_container">
  <input type="submit" name="repository_continuebutton" id="repository_continuebutton" class="tl_submit" value="<?php echo $text['continue']; ?>" />
</div>

</div>
</form>
</div>
