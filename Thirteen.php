<?php

//
// Thirteen skin
// Thirteen.php
//
// Version: 1.0
// Author: Fränz Friederes <fraenz@frieder.es>
// Link: https://github.com/the2f/Thirteen
//

// prevent direct file access
if (!defined('MEDIAWIKI'))
	die(- 1);

class SkinThirteen extends SkinTemplate
{
	var $useHeadElement = true;

	function initPage(OutputPage $out)
	{
		parent::initPage($out);

		// skin details
		$this->skinname  = 'thirteen';
		$this->stylename = 'thirteen';
		$this->template  = 'ThirteenTemplate';
	}

	function setupSkinUserCss(OutputPage $out)
	{
		global $wgHandheldStyle;

		parent::setupSkinUserCss($out);

		// append style links
		$out->addStyle('thirteen/main.css', 'screen');
		$out->addStyle('thirteen/print.css', 'print');
	}

}

class ThirteenTemplate extends BaseTemplate {

	public function execute()
	{
		$skin = $this->data['skin'];

		wfSuppressWarnings();

		$this->html('headelement');

		$available_personal_tools = array(
			'pt-createaccount', 'pt-login', 'pt-anonlogin',
			'pt-userpage', 'pt-preferences', 'pt-logout');

		// collect tools
		$tools = array();
		$content_views = $this->data['content_navigation']['views'];

		foreach (array('view', 'edit', 'history') as $item)
			if (isset($content_views[$item]))
				$tools[] = array(
					'class' => $item,
					'title' => $content_views[$item]['text'],
					'href' => $content_views[$item]['href'],
					'selected' => ($content_views[$item]['class'] == 'selected')
				);

?>

<div id="wrapper">

	<header id="header-site">
		<div class="container">
			<ul id="account_links">
				<?php foreach($this->getPersonalTools() as $item) {
					$id = htmlspecialchars($item['links'][0]['single-id']);
					$href = htmlspecialchars($item['links'][0]['href']);
					$text = htmlspecialchars($item['links'][0]['text']);

					if (array_search($id, $available_personal_tools) !== false) { ?>
				<li id="<?= $id ?>">
					<a href="<?= $href ?>"><?= $text ?></a>
				</li>
				<?php } } ?>
			</ul>
			<hgroup>
				<a href="<?= $this->data['nav_urls']['mainpage']['href'] ?>">
					<h1 style="background-image: url(<?= $this->data['logopath'] ?>);">
						<?= htmlspecialchars($this->data['sitename']) ?>
					</h1>
				</a>
			</hgroup>
		</div>
	</header>

	<header id="header-content">
		<div class="container">
			<ul id="tools">
				<?php foreach ($tools as $tool) { ?>
				<li class="<?= $tool['class'] . ($tool['selected'] ? ' selected' : '') ?>">
					<a href="<?= $tool['href'] ?>"><?= $tool['title'] ?></a>
				</li>
				<?php } ?>
			</ul>
			<h1><?php
			
			// this script separates (by /) the title intro the segments
			$title = htmlspecialchars($this->data['title']);

			if (
				// at least one separation?
				strpos($title, '/') > 0
				// can this title be separated?
				&& $this->data['content_navigation']['views']['view']['class'] == 'selected'
			) {
				// actual title split
				$parts = split('/', $title);
				$base_url = '';

				for ($i = 0; $i < count($parts) - 1; $i ++)
				{
					$part = $parts[$i];

					if ($base_url != '') $base_url .= '/';

					// make an url out of this part
					$base_url .= urlencode(str_replace(' ', '_', $part));

					// insert title into article path
					$url = str_replace('$1', $base_url, $this->data['articlepath']);

					// write segment
					echo '<a class="level" href="' . $url . '">' . $part . '</a>';
					echo '<span class="separator">&#9658;</span>';
				}

				// write actual page title
				echo '<span class="level highest">' . $parts[count($parts) - 1] . '</span>';
			}
			else
			{
				// just print out the title
				echo $title;
			}

			?></h1>
		</div>
	</header>

	<div id="content">
		<div class="container">
			<?php $this->html('bodytext') ?>
		</div>
	</div>

	<footer>
		<div class="container">

			<?php if (count($this->data['content_navigation']['actions']) > 0) { ?>

			<ul id="page-actions">

				<?php foreach ($this->data['content_navigation']['actions'] as $item) : ?>
				<li id="<?= $item['id'] ?>" class="more <?= $item['class'] ?>"><a href="<?php echo htmlspecialchars($item['href']) ?>"><?php echo htmlspecialchars($item['text']) ?></a></li>
				<?php endforeach; ?>

			</ul>

			<?php } ?>

			<?php foreach($this->getFooterLinks() as $category => $links) : ?>
			
			<ul id="footer-<?php echo $category ?>">
				<?php foreach($links as $link) : ?>
					<li id="footer-<?php echo $category ?>-<?php echo $link ?>"><?php $this->html($link) ?></li>
				<?php endforeach; ?>
			</ul>

			<?php endforeach; ?>

		</div>
	</footer>

</div>

<?php $this->printTrail() ?>

</body>
</html>

<?php

	}

}