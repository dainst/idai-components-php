<?php

namespace idai {

	class components {

		public $settings = null;

		private $_defaults = array(
				"path" => "",

				"webpath" => "",

				"return" => false, // return or echo everything?

				"logo" => array(
					"src" => "small logo for header",
					"text" => "ProjectTitle",
					"href" => "/",
					"href2" => false // for text only
				),

				"search" => array(
					"invisible" => false,
					"href" => "someurl.php",
					"method" => 'get',
					"onsubmit" => '',
					"label" => "SEARCH",
					"name" => 'q',
					"params" => array()
				),

				"buttons" => array(

					"languagemenu" => array(
						"label" => "",
						"src" => "language-icon.png",
						"submenu" => array()
					),

					"usermenu"	=>  array(
						"label" => "Usermenu",
						"submenu" => array(
							"logout" => array(
								"href" => "#",
								"label" => "Sign Out",
								'glyphicon' => 'log-out'
							)
						)
					),
					"login" => array(
							"href" => "#",
							"label" => "Sign In",
							'glyphicon' => 'log-in'
					),
					"register" => array(
						"href" => "#",
						"label" => "Sign Up"
					),
					"zzzzcontact" => array(
						"href" => "#",
						'glyphicon' => 'envelope'
					),

				),

				"user" => array(
					"name" => false
				),

				"version" => '1.0',

				"institutions" => array(
					"dai" => array(
						"title" => "dai_logo",
						"src" => 'img/institutions/logo_dai.png',
						"href" => "http://www.dainst.org"
					)
				),

				"footer_links" => array(
					'licence' => array(
						'text' => 'Licensed under',
						'label' => 'Creative Commons',
						'href' => 'http://creativecommons.org/licenses/by-nc-nd/3.0/',
						'target' => '_blank'
					),
					'contact' => array(
						'text' => ' Report bugs to',
						'label' => 'somemail@dainst.org',
						'href' => 'mailto:somemail@dainst.org',
					)
				),

				"footer_classes" => array(),

				// select jquery+navbar or jquery+bootstrap
				// include may contain a string also, to include scripts on different positions manually, but true means in <head>
				"scripts"	=> array(
					'jquery' 	=> array(
						'include' 	=>	true,
						'src'		=>	'script/jquery-2.2.4.min.js'
					),
					'bootstrap' => array(
						'include' 	=>	false,
						'src'		=>	'script/bootstrap.min.js'
					),
					'navbar' => array(
						'include' 	=>	true,
						'src'		=>	'script/idai-navbar.js'
					)
				),

				"styles"	=>	array(
					'idai-components.min' => array(
						'include'	=>	true,
						'src'		=>	'style/idai-components.min.css'
					),
					'idai-components' => array(
						'include'	=>	false,
						'src'		=>	'style/idai-components.css'
					),
					'idai-navbar' => array(
						'include'	=>	true,
						'src'		=>	'style/idai-navbar.css'
					)
				)

		);

		function __construct($settings = array()) {

			if (!isset($settings['webpath'])) {
				throw new \Exception("Webpath for idai-components-php not set");
			}

			// default callbacks
			$this->_defaults['buttons']['register']['show'] = function($btn, $set) {
				$btn['invisible']	= ($set['user']['name']);
				return $btn;
			};
			$this->_defaults['buttons']['usermenu']['show'] = function($btn, $set) {
				$btn['label']		= $set['user']['name'];
				$btn['invisible']	= (!$set['user']['name']);
				return $btn;
			};
			$this->_defaults['buttons']['login']['show'] = function($btn, $set) {
				$btn['invisible']	= ($set['user']['name']);
				return $btn;
			};

			// default paths
			$this->_defaults['buttons']['languagemenu']['src'] = $settings['webpath'] . 'img/language-icon.png';

			// construct settings
			$set = $this->_defaults;
			$set['projects'] = json_decode(file_get_contents(realpath(__DIR__ . '/projects.json')));
			$set = array_replace_recursive($set, $settings);
			$this->settings = $set;
			$this->path = realpath(__DIR__);




		}

		/**
		 * includes the required stylsheets and javascripts
		 * include in <head>
		 */
		function header($path = null) {

			if ($path) {
				$this->settings['webpath'] = $path;
			} else {
				$path = $this->settings['webpath'];
			}
			//$path = realpath($path);

			$code = "<link type='image/x-icon' href='{$path}img/favicon.ico' rel='icon' />
	  				<link type='image/x-icon' href='{$path}img/favicon.ico' rel='shortcut icon' />";

			$code .= $this->getStyles();
			$code .= $this->getScripts();


			if ($this->settings['return']) {
				return $code;
			} else {
				echo $code;
			}
		}

		/**
		 * return string including alls registered scripts
		 *
		 * @return string
		 */
		function getScripts($position = true) {
			$code = "";
			foreach ($this->settings['scripts'] as $script) {
				if ($script['include'] === $position) {
					$url = $this->getUrl($script['src']);
					$code .= "\n<script type='text/javascript' src='$url'></script>";
				}
			}
			return $code;
		}

		/**
		 * @return string
		 * the same for stylesheets
		 */
		function getStyles($position = true) {
			$code = "";
			foreach ($this->settings['styles'] as $style) {
				if ($style['include'] === $position) {
					$media = isset($style['media']) ? $style['media'] : 'screen';
					$url = $this->getUrl($style['src']);
					$code .= "<link rel='stylesheet' href='$url' type='text/css' media='$media' />";
				}
			}
			return $code;
		}

		function getUrl($url) {
			return (substr($url, 0, 4) == 'http') ? $url : $this->settings['webpath'] . $url;
		}


		/**
		 * render the blue navbar
		 * include after body
		 *
		 * @param some $content directly insert in the navbar
		 * @return string
		 */
		function navbar($content) {
			if ($this->settings['return']) {
				$return = ob_start();
			}
			?>
			<div id='dai_navbar' class='navbar navbar-default navbar-fixed-top'>
				<div id="navbar_left">

					<div class="pull-left">
						<ul class="nav navbar-nav">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle='dropdown'><img src="<?php echo $this->settings['webpath']; ?>img/kleinergreif.png" id="brand-img"> <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<?php foreach($this->settings['projects'] as $k=>$v) { ?>
										<?php if (count($v) == 2) { ?>
											<?php echo "<li><a href='{$v[0]}' target='_blank'>{$v[1]}</a></li>" ?>
										<?php } else { ?>
											<li class="divider"></li>
										<?php } ?>
									<?php } ?>
								</ul>
							</li>
						</ul>
					</div>
					<a href="<?php echo $this->settings['logo']['href']; ?>" id="projectLogo">
						<?php echo "<img src='{$this->settings['logo']['src']}' class='pull-left'>"; ?>
					</a>
					<a href="<?php echo $this->settings['logo']['href2'] ? $this->settings['logo']['href2'] : $this->settings['logo']['href']; ?>" id="projectLogoText">
						<?php echo "<span id='project_title' class='pull-left'>{$this->settings['logo']['text']}</span>"; ?>
					</a>
				</div>

				<?php /* */ ?>

				<?php /* this button for mobile menu and stuff */ ?>
				<button class="navbar-toggle" type="button">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

				<div class="navbar-collapse collapse" id="collapsable_navbar">
					<?php if (!$this->settings['search']['invisible'] and is_array($this->settings['search']) and count($this->settings['search'])) { ?>
						<form
							class="navbar-left navbar-form input-group form-inline"
							role="search"
							action="<?php echo $this->settings['search']['href']; ?>"
							method="<?php echo $this->settings['search']['method']; ?>"
							onsubmit="<?php echo $this->settings['search']['onsubmit']; ?>"
						>
							<input
								class="form-control"
								placeholder="<?php echo $this->settings['search']['label']; ?>"
								name="<?php echo $this->settings['search']['name']; ?>"
								type="text"
							>
							<?php foreach ($this->settings['search']['params'] as $param => $val) {?>
								<input type='hidden' name='<?php echo $param; ?>' value='<?php echo $val; ?>'>
							<?php } ?>
							<span class="navbar-left input-group-btn">
								<button type="submit" class="btn btn-default" data-nav="search">
									<span class="glyphicon glyphicon-search"></span>
								</button>
							</span>
						</form>
					<?php } ?>

					<ul class="nav navbar-nav navbar-right">
						<?php
						ksort($this->settings['buttons']);
						$i = 0;
						if (count($this->settings['buttons'])) {
							//echo '<li><div class="btn-group btn-group-sm">';
							foreach ($this->settings['buttons'] as $id => $btn) {
								// mark most right btn
								if (is_array($btn) and ($i++ == count($this->settings['buttons']) - 1)) {
									$btn['class'] .= ' navbar_mostright';
								}
								$this->_navbar_button($btn, $id);

							}
							//echo '</div></li>';
						}
						?>
					</ul>
					<?php echo $content; ?>
				</div>

			</div>
			<?php
			if ($this->settings['return']) {
				return ob_get_clean();
			}
		}

		private function _navbar_button(&$data, $id) {
			//print_r($data); die("!");
			if (!is_array($data)) {
				 echo (string) $data;
				 return;
			}

			// call transform function if available
			if (isset($data['show']) and (gettype($data['show']) == 'object')) {
				$data = call_user_func($data['show'], $data, $this->settings);
			}


			if (!isset($data['invisible']) or !$data['invisible']) {

				if (isset($data['submenu']) and count($data['submenu'])) {
					echo "<li class='dropdown {$data['class']}' id='navbar-item-{$id}'>";
					echo "<a href='#' class='dropdown-toggle' data-toggle='dropdown'>";
					echo (isset($data['glyphicon'])) ? "<span class='glyphicon glyphicon-{$data['glyphicon']}'></span>" : '';
					echo (isset($data['src'])) ? "<img class='nav-icon' src='{$data['src']}' alt='icon'>" : '';
					echo "<span class='nav-label'>{$data['label']}</span> <b class='caret'></b></a>";
					echo '<ul class="dropdown-menu">';
					foreach ($data['submenu'] as $sub) {
						echo "<li><a href='{$sub['href']}' onclick='{$sub['onclick']}'>";
						echo (isset($sub['glyphicon'])) ? "<span class='glyphicon glyphicon-{$sub['glyphicon']}'></span>" : '';
						echo "{$sub['label']}</a></li>";
					}
					echo "</ul>";
					echo "</li>";
					return;
				}


				echo "<li class='{$data['class']}' id='navbar-item-{$id}'>"; 			//class='btn btn-sm btn-default navbar-btn'
				echo "<a href='{$data['href']}' onclick='{$data['onclick']}'>";
				echo (isset($data['glyphicon'])) ? "<span class='glyphicon glyphicon-{$data['glyphicon']}'></span>" : '';
				echo (isset($data['src'])) ? "<img class='nav-icon' src='{$data['src']}' alt='icon'>" : '';
				echo "<span class='nav-label'>{$data['label']}</span></li>";
			}
		}


		function footer() {
			if ($this->settings['return']) {
				$return = ob_start();
			}
			?>
			<div id="idai-footer" class="row <?php echo implode(" ", $this->settings["footer_classes"]); ?>">
				<div class="col-md-12 text-center">
					<p class="idai-footer-institutions">
						<?php foreach ($this->settings['institutions'] as $inst) { ?>
							<a href="<?php echo $inst['href']; ?>">
								<?php $logo = (substr($inst['url'], 1, 4) == 'http') ? $inst['src'] : $this->settings['webpath'] . $inst['src']; ?>
								<img class="logoImage" alt="<?php echo $inst['title']; ?>" src="<?php echo $logo?>">
							</a>
						<?php } ?>
					</p>
					<p class="idai-footer-links">
						<?php echo implode(' | ', array_map(array($this,_footer_link), $this->settings['footer_links'])); ?>
					</p>
					<?php if (isset($this->settings['version']) and $this->settings['version']) { ?>
						<p><small><?php echo $this->settings['version']; ?></small></p>
					<?php } ?>
				</div>
			</div>
			<?php
			if ($this->settings['return']) {
				return ob_get_clean();
			}

		}

		private function _footer_link($link) {


			$id = (isset($link['id'])) ? "id='{$link['id']}'" : '';

			if ((isset($link['moreinfo']) and $link['moreinfo'] != '')) {
				return "{$link['text']} <span class='idai-infobox-toggle' $id>{$link['label']}<span class='idai-infobox'>{$link['moreinfo']}</span></span>";
			} else {
				$target = (isset($link['target']) and $link['target']) ? 'target="' . $link['target'] . '"' : '';
				$onclick = "href='{$link['href']}' $target";
				return "{$link['text']} <a $onclick $id>{$link['label']} {$link['moreinfo']}</a>";
			}



		}

	}


}
?>
