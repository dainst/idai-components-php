<?php 

namespace idai {
	
	class components {
		
		public $settings = null;
		
		private $_defaults = array(
				"path" => "",
				
				"webpath" => "",
				
				"logo" => array(
					"src" => "small logo for header",
					"text" => "ProjectTitle"
				),
				
				"search" => array(
					"invisible" => false,
					"href" => "someurl.php",
					"method" => 'get',
					"onsubmit" => '',
					"label" => "SEARCH",
					"name" => 'q'
				),

				"buttons" => array(
					"login" => array(
						"href" => "#",
						"label" => "Sign In",
						'glyphicon' => 'log-in',
					),
					"register" => array(
						"href" => "#",
						"label" => "Sign Up"
					),
					"contact" => array(
						"href" => "#",
						'glyphicon' => 'envelope'
					)
				),
				
				"version" => '1',
				
				"institutions" => array(
					"dai" => array(
						"title" => "dai_logo",
						"src" => 'img/institutions/logo_dai.png',
						"http://www.dainst.org"
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
				)
				

		);
		
		function __construct($settings = array()) {
			
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
			?>
			<link type="image/x-icon" href="<?php echo $path;?>img/favicon.ico" rel="icon" />
  			<link type="image/x-icon" href="<?php echo $path;?>img/favicon.ico" rel="shortcut icon" />
			<link rel="stylesheet" href="<?php echo $path;?>style/idai-components.min.css" type="text/css" media="screen" />
			<link rel="stylesheet" href="<?php echo $path;?>style/idai-navbar.css" type="text/css" media="screen" />
			<script type="text/javascript" src="<?php echo $path;?>script/idai-navbar.js"></script>
		<?php }
		
		/**
		 * render the blue navbar
		 * include after body
		 * 
		 * 
		 */
		function navbar() { ?>
			<div id='dai_navbar' class='navbar navbar-default navbar-fixed-top'>
				<div id="navbar_left">
				
					<div class="pull-left">
						<ul class="nav navbar-nav">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle"><img src="<?php echo $this->settings['webpath']; ?>img/kleinergreif.png" id="brand-img"> <b class="caret"></b></a>
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
					<a href="/" id="projectLogo">
						<?php echo "<img src='{$this->settings['logo']['src']}' class='pull-left'></a>"; ?>
						<?php echo "<span id='project_title' class='pull-left'>{$this->settings['logo']['text']}</span>"; ?>
					</a>
				</div>
			 
				<?php /* */ ?>
			
				<div class="navbar-collapse collapse">
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
							<span class="navbar-left input-group-btn">
								<button type="submit" class="btn btn-default" data-nav="search">
									<span class="glyphicon glyphicon-search"></span>
								</button>
							</span>
						</form>
					<?php } ?>
				
					<ul class="nav navbar-nav navbar-right">
						<?php 
						if (count($this->settings['buttons'])) {
							//echo '<li><div class="btn-group btn-group-sm">';
							foreach ($this->settings['buttons'] as $btn) {
								
								// mark most right btn
								if (is_array($btn) and ($i++ == count($this->settings['buttons']) - 1)) {
									$btn['class'] .= ' navbar_mostright';
								}
								$this->_navbar_button($btn); 
								
							}
							//echo '</div></li>';
						}
						?>
					</ul>
				</div>
			</div>
		<?php }
		
		private function _navbar_button($data) {
			//print_r($data); die("!");
			if (!is_array($data)) {
				 echo (string) $data;
				 return;
			}
			
			if (isset($data['submenu']) and count($data['submenu'])) {
				echo "<li class='dropdown {$data['class']}'>";
				echo "<a href='#' class='dropdown-toggle' data-toggle='dropdown'>{$data['label']} <b class='caret'></b></a>";
				echo '<ul class="dropdown-menu">';
				foreach ($data['submenu'] as $sub) {
					echo "<li><a href='{$sub['href']}' onclick='{$sub['onclick']}'>{$sub['label']}</a></li>";
				}
				echo "</ul>";
				echo "</li>";
				return;			
			}
			
			//class='btn btn-sm btn-default navbar-btn'
			echo "<li class='{$data['class']}'>";
			echo "<a type='button' href='{$data['href']}' onclick='{$data['onclick']}'>{$data['label']}";
			if (isset($data['glyphicon'])) {
				echo "<span class='glyphicon glyphicon-{$data['glyphicon']}'></span>";
			}
			echo "</a></li>";
		}
		
		
		function footer() { ?>
			<div class="row">
				<div class="col-md-12 text-center">
					<p>
						<?php foreach ($this->settings['institutions'] as $inst) { ?>
							<a href="<?php echo $inst['url']; ?>">
								<?php $logo = (substr($inst['url'], 1, 4) == 'http') ? $inst['src'] : $this->settings['webpath'] . $inst['src']; ?>
								<img class="logoImage" alt="<?php echo $inst['title']; ?>" src="<?php echo $logo?>">
							</a>
						<?php } ?>
					</p>
					<p>						
						<?php echo implode(' | ', array_map(array($this,_footer_link), $this->settings['footer_links'])); ?>
					</p>
					<p ng-show="version">
						<small><?php echo $this->settings['version']; ?></small>
					</p>
				</div>
			</div>
		<?php }
		
		private function _footer_link($link) {
			$target = (isset($link['target']) and $link['target']) ? 'target="' . $link['target'] . '"' : '';	
			return "{$link['text']} <a href='{$link['href']}' $target>{$link['label']}</a>";
		}
		
	}
	
	
}
?>